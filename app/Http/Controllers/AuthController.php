<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterNewUserRequest $request)
    {
        $type = $request->has('email') ? 'email' : 'mobile';
        $value = $request->input($type, 'email');
        // TODO: generating random code to send to registering user
        $code = '123456';

        // TODO: the cache expiration date most be set as a config value for more abstraction
        Cache::put('user-auth-register-' . $value, compact('type', 'code'), now()->addDay());
        // adding register api route | creating a special request to handle registration validation | adding the auth controller and also adding the register functionality to it | fixing users table migration to be able to register users via either email or mobile
        // TODO: sending message through email/mobile to the user for completing THE 'registration'
        Log::info('SENDING-REGISTER-CODE-MESSAGE-TO-USER', ['code' => $code]);
        return response(['message' => 'کاربر با موفقیت ثبت موقت شد'], 200);
    }
}
