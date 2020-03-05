<?php

namespace App\Http\Controllers;

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\WrongVerifyCodeException;
use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyResendRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Services\AuthenticationService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();
            $code = AuthenticationService::RegisteringService($request);
            DB::commit();
            return response(['message' => 'کاربر با موفقیت ثبت موقت شد', 'code' => $code], 200);

        } catch (Exception $exception) {
            if ($exception instanceof UserAlreadyExistsException) {
                throw new UserAlreadyExistsException();
            }
            Log::error('exception on registering user: ' . $exception);
            DB::rollBack();
            return response($exception->getMessage(), $exception->getCode());
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
        $user = AuthenticationService::RegisteringVerificationService($request);

        return response($user, 200);
    }

    /**
     * @param RegisterVerifyResendRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function register_verify_resend(RegisterVerifyResendRequest $request)
    {
        [$user, $field] = AuthenticationService::RegisteringVerificationResend($request);

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
