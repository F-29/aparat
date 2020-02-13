<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(["namespace" => "\Laravel\Passport\Http\Controllers"], function ($router) {
    $router->post('login', [
        'as' => 'login',
        'middleware' => ['throttle'],
        'uses' => 'AccessTokenController@issueToken'
    ]);
});

Route::post('register', 'AuthController@register')->name('register');

Route::post('register-verify', 'AuthController@register_verify')->name('register-verify');

Route::post('register-verify-resend', 'AuthController@register_verify_resend')->name('register-verify-resend');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
