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

Route::get('register-verify/{code}/{data_key}', 'AuthController@register_verify')->name('register-verify');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
