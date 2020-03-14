<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/**
 * Authentication Routes
 */
Route::group([], function (Router $Router) {
    $Router->group(["namespace" => "\Laravel\Passport\Http\Controllers"], function (Router $router) {
        $router->post('login', [
            'as' => 'login',
            'middleware' => ['throttle'],
            'uses' => 'AccessTokenController@issueToken',
        ]);
    });

    $Router->post('register', [
        'as' => 'register',
        'uses' => 'AuthController@register',
    ]);

    $Router->post('register-verify', [
        'as' => 'register-verify',
        'uses' => 'AuthController@register_verify',
    ]);

    $Router->post('register-verify-resend', [
        'as' => 'register-verify-resend',
        'uses' => 'AuthController@register_verify_resend',
    ]);
});

/**
 * User Related Routes
 */
Route::group(["middleware" => "auth:api"], function (Router $Router) {
    $Router->post('change-email', [
        'middleware' => ['auth:api'],
        'as' => 'change.email',
        'uses' => 'UserController@changeEmail',
    ]);

    $Router->post('change-email-submit', [
        'middleware' => ['auth:api'],
        'as' => 'change.email.submit',
        'uses' => 'UserController@changeEmailSubmit'
    ]);
});

/**
 * Channel Routes
 */
Route::group(["middleware" => "auth:api", 'prefix' => '/channel'], function (Router $Router) {
    $Router->post('/', [
        'as' => 'channel.upload.banner',
        'uses' => 'ChannelController@uploadBanner',
    ]);

    $Router->put('/{id?}', [
        'as' => 'channel.update',
        'uses' => 'ChannelController@update',
    ]);

    $Router->post('/socials', [
        'as' => 'channel.update.socials',
        'uses' => 'ChannelController@updateSocials',
    ]);
});
