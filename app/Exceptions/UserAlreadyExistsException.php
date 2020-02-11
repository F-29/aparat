<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class UserAlreadyExistsException extends Exception
{
    /**
     * Report the exception.
     *
     * @param $request
     * @return void
     */
    public function report()
    {
        Log::info('USER-ALREADY-EXISTS');
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(['message' => 'user already exists', 'stack' => $this->getMessage()], 400);
    }
}
