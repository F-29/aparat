<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(RegisterNewUserRequest $request)
    {
        $type = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($type, 'email');
        // TODO: generating random code to send to registering user
        $code = '123456';

        // TODO: the cache expiration date most be set as a config value for more abstraction
        Cache::put('user-auth-register-' . $value, compact('type', 'code'), config('auth.register_cache_expiration', 1440));

        // TODO: sending message through email/mobile to the user for completing THE 'registration'
        Log::info('SENDING-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
        return response(['message' => 'کاربر با موفقیت ثبت موقت شد'], 200);
    }
}
