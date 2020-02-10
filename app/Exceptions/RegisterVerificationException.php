<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class RegisterVerificationException extends Exception
{
    /**
     * Report the exception.
     *
     * @param $request
     * @return void
     */
    public function report()
    {
        Log::info('REGISTER-CODE-IS-WRONG');
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(['message' => (string)$this->getMessage()], 400);
    }
}
