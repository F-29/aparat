<?php


namespace App\Http\Services;


use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\WrongVerifyCodeException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyResendRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class AuthenticationService
{
    /**
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|int
     * @throws UserAlreadyExistsException
     * @throws \Exception
     */
    public static function RegisteringService(RegisterNewUserRequest $request)
    {
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

        return $code;
    }

    /**
     * @param RegisterVerifyResendRequest $request
     * @return Authenticatable
     * @throws UserAlreadyExistsException
     * @throws WrongVerifyCodeException
     */
    public static function RegisteringVerificationService(RegisterVerifyUserRequest $request)
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

        return $user;
    }

    /**
     * @param RegisterVerifyResendRequest $request
     * @return array
     */
    public static function RegisteringVerificationResend(RegisterVerifyResendRequest $request)
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

        return [$user, $field];
    }
}
