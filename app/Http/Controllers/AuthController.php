<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\WrongVerifyCodeException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyResendRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use Illuminate\Support\Facades\Cache; // only with cache registration method
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function register(RegisterNewUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $field = $request->getFieldName();
            $value = $request->getFieldValue();
            $code = random_verification_code();
            if ($user = User::where($field, $value)->first()) {
                if ($user->verified_at) {
                    throw new UserAlreadyExistsException();
                }
                return response(['message' => 'verification code have already been sent'], 200);
            }

            $user = User::create([
                'type' => User::TYPE_USER,
                'verify_code' => $code,
                $field => $value,
            ]);

            // TODO: sending message through email/mobile to the user for completing THE 'registration'
            Log::info('SENDING-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);

            DB::commit();
            return response(['message' => 'کاربر با موفقیت ثبت موقت شد', 'code' => $code], 200);

        } catch (Exception $exception) {
            Log::error('exception on registering user: ' . $exception);
            DB::rollBack();
            dd($exception);
            return response(['message' => 'Error in registration process'], 500);
        }

    }

    /**
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws UserAlreadyExistsException
     * @throws WrongVerifyCodeException
     * @throws ModelNotFoundException
     */
    public function register_verify(RegisterVerifyUserRequest $request)
    {
        $field = $request->getFieldName();
        $code = $request->code();

        $user = User::where([
            $field => $request->getFieldValue(),
        ])->first();

        if ($code !== $user->verify_code) {
            throw new WrongVerifyCodeException();
        }

        if (empty($user)) {
            throw new ModelNotFoundException('user have already been verified');
        }

        if (!empty($user->verified_at)) {
            throw new UserAlreadyExistsException('user already exists');
        }


        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user, 200);
    }

    /**
     * @param RegisterVerifyResendRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function register_verify_resend(RegisterVerifyResendRequest $request)
    {
        $field = $request->getFieldName();
        $value = $request->getFieldValue();

        $user = User::where($field, $value)->first();

        if (empty($user)) {
            throw new ModelNotFoundException('no user found');
        }

        if ($user->verified_at) {
            throw new ModelNotFoundException('user have already been verified');
        }

        if (!empty($user)) {
            $dateDiff = now()->diffInMinutes($user->updated_at);

            if ($dateDiff > config('auth.resend_verification_code_time_diff', 60)) {
                $user->verify_code = random_verification_code();
                $user->save();
            }

            // TODO: sending message through email/mobile to the user
            Log::info('RESEND-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $user->verify_code]);

            return response([
                'message' => 'verification code sent, please check your ' . ($field === 'mobile' ? 'phone' : 'email')
            ], 200);
        }
    }
}
