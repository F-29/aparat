<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class WrongVerifyCodeException extends Exception
{
    /**
     * Report the exception.
     *
     * @param $request
     * @return void
     */
    public function report()
    {
        Log::info('WRONG-VERIFY-CODE');
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(['message' => 'request contains a wrong verify code'], 400);
    }
}
