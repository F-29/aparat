<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyExistsException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use Illuminate\Support\Facades\Cache; // only with cache registration method
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function register(RegisterNewUserRequest $request)
    {
        $type = $request->getFieldName();
        $value = $request->getFieldValue();
        $code = random_int(111111, 999999);
        if ($user = User::where($type, $value)->first()) {
            if ($user->verified_at) {
                throw new UserAlreadyExistsException();
            }
            return response(['message' => 'verification code have already been sent'], 200);
        }
        try {
            User::create([
                'type' => User::TYPE_USER,
                'verify_code' => $code,
                $type => $value,
            ]);
        } catch (Exception $e) {
            throw new UserAlreadyExistsException($e);
        }
        // TODO: sending message through email/mobile to the user for completing THE 'registration'
        Log::info('SENDING-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
        return response(['message' => 'کاربر با موفقیت ثبت موقت شد', 'code' => $code], 200);
    }

    /**
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register_verify(RegisterVerifyUserRequest $request)
    {
        $type = $request->getFieldName();
        $code = $request->code();

        $user = User::where([
            $type => $request->getFieldValue(),
            'verify_code' => $code,
        ])->first();

        if (empty($user)) {
            throw new ModelNotFoundException('no user found or user have already been verified');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user, 200);
    }
}
