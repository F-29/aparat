<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    const EMAIL_CHANGE_CACHE_KEY = 'change.email.for.';

    /**
     * @param ChangeEmailRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function changeEmail(ChangeEmailRequest $request)
    {
        $email = $request->email;
        $userID = auth()->id();
        $code = random_verification_code();
        $expirationDate = now()->addMinutes(config('auth.change_email_cache_expiration', 1440));

        Cache::put(self::EMAIL_CHANGE_CACHE_KEY . $userID, compact('email', 'code'), $expirationDate);

        // TODO: sending email to the user's new email address (for now we send the code in the response)
        return response([
            'message' => "an email have been sent to you, please check inbox (if you did'nt find it check your spam and other subjects)",
            'code' => $code
        ], 200);
    }

    /**
     * @param ChangeEmailSubmitRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeEmailSubmit(ChangeEmailSubmitRequest $request)
    {
        $userID = auth()->id();
        $cacheKey = self::EMAIL_CHANGE_CACHE_KEY . $userID;
        $cache = Cache::get($cacheKey);
        if (empty($cache) || $cache['code'] != $request->code) {
            dd($cache, $request->all());
            return response([
                'message' => 'wrong inputs or bad request'
            ], 400);
        }

        $user = auth()->user();
        $user->email = $cache['email'];
        $user->save();
        Cache::forget($cacheKey);

        return response([
            'message'=> 'email changed successfully!'
        ], 200);
    }
}
