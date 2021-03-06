<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ChangeEmailSubmitRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Services\UserService;

class UserController extends Controller
{

    /**
     * @param ChangeEmailRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function changeEmail(ChangeEmailRequest $request)
    {
        $code = UserService::ChangeEmailService($request);

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
        return UserService::ChangeEmailSubmitService($request);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return UserService::ChangePasswordService($request);
    }
}
