<?php

namespace App\Http\Controllers;

//use App\Exceptions\RegisterVerificationException; // only with cache registration method
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
        $type = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($type, 'email');
        $code = random_int(111111, 999999);
        try {
            User::create([
                'type' => User::TYPE_USER,
                'verify_code' => $code,
                $type => $value,
            ]);
        } catch (QueryException $e) {
            throw new UserAlreadyExistsException($e);
        }
//        $expiration = config('auth.register_cache_expiration', 1440); // only with cache registration method

//        Cache::put('user-auth-register-' . $value, compact('type', 'code'), $expiration); // only with cache registration method

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
        $code = $request->code;

        $user = User::where('verify_code', $code)->first();

        if (empty($user)) {
            throw new ModelNotFoundException('کاربری با کد مورد نظر یافت نشد');
        }

        $user->verify_code = null;
        $user->verified_at = now();
        $user->save();

        return response($user, 200);
//        // TODO: decide that cached data most be destroyed after being used or wait for expiration. P.S. : for now we wait for expiration
//        $register_data = Cache::get('user-auth-register-' . $data_key); // only with cache registration method

//        if ($register_data && $register_data['code'] == $code) {
//              // only with cache registration method
//        }
//        throw new RegisterVerificationException('something went wrong check email and verify code again'); // only with cache registration method
    }
}
