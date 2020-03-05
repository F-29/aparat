<?php


namespace App\Http\Services;


use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use Illuminate\Support\Facades\Cache;

class UserEmailService
{
    const EMAIL_CHANGE_CACHE_KEY = 'change.email.for.';

    public static function ChangeEmailService(ChangeEmailRequest $request)
    {
        $email = $request->email;
        $userID = auth()->id();
        $code = random_verification_code();
        $expirationDate = now()->addMinutes(config('auth.change_email_cache_expiration', 1440));

        Cache::put(self::EMAIL_CHANGE_CACHE_KEY . $userID, compact('email', 'code'), $expirationDate);

        return $code;
    }

    public static function ChangeEmailSubmitService(ChangeEmailSubmitRequest $request)
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
    }
}
