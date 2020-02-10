<?php

namespace App\Http\Controllers;

use App\Exceptions\RegisterVerificationException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function register(RegisterNewUserRequest $request)
    {
        $type = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($type, 'email');
        $code = random_int(111111, 999999);
        $expiration = config('auth.register_cache_expiration', 1440);

        Cache::put('user-auth-register-' . $value, compact('type', 'code'), $expiration);

        // TODO: sending message through email/mobile to the user for completing THE 'registration'
        Log::info('SENDING-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
        return response(['message' => 'کاربر با موفقیت ثبت موقت شد', 'code' => $code], 200);
    }

    /**
     * @param $code
     * @param $data_key
     * @throws RegisterVerificationException
     */
    public function register_verify($code, $data_key)
    {
        // TODO: decide that cached data most be destroyed after being used or wait for expiration. P.S. : for now we wait for expiration
        $register_data = Cache::get('user-auth-register-' . $data_key);

        if ($register_data && $register_data['code'] == $code) {
            dd("success!", $code, $data_key, $register_data);
        }
        throw new RegisterVerificationException('something went wrong check email and verify code again');
    }
}
