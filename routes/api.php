<?php

use Illuminate\Support\Facades\Route;

Route::group(["namespace" => "\Laravel\Passport\Http\Controllers"], function ($router) {
    $router->post('login', [
        'as' => 'login',
        'middleware' => ['throttle'],
        'uses' => 'AccessTokenController@issueToken'
    ]);
});

Route::post('register', [
    'as' => 'register',
    'uses' => 'AuthController@register'
]);

Route::post('register-verify', [
    'as' => 'register-verify',
    'uses' => 'AuthController@register_verify'
]);

Route::post('register-verify-resend', [
    'as' => 'register-verify-resend',
    'uses' => 'AuthController@register_verify_resend'
]);

Route::post('change-email', [
    'middleware' => ['auth:api'],
    'as' => 'change.email',
    'uses' => 'UserController@changeEmail'
]);

Route::post('change-email-submit', [
    'middleware' => ['auth:api'],
    'as' => 'change.email.submit',
    'uses' => 'UserController@changeEmailSubmit'
]);
