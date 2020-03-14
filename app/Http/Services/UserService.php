<?php


namespace App\Http\Services;


use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    const EMAIL_CHANGE_CACHE_KEY = 'change.email.for.';

    /**
     * @param ChangeEmailRequest $request
     * @return int
     * @throws \Exception
     */
    public static function ChangeEmailService(ChangeEmailRequest $request)
    {
        $email = $request->email;
        $userID = auth()->id();
        $code = random_verification_code();
        $expirationDate = now()->addMinutes(config('auth.change_email_cache_expiration', 1440));

        Cache::put(self::EMAIL_CHANGE_CACHE_KEY . $userID, compact('email', 'code'), $expirationDate);

        return $code;
    }

    /**
     * @param ChangeEmailSubmitRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    public static function ChangePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();

            if (!Hash::check($request->old_password, $user->password)) {
                return response(['message' => 'credentials wont match'], 400);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return response(['message' => 'changed'], 200);

        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'something went wrong!', 'P.S.' => 'in our side'], 500);
        }
    }
}
