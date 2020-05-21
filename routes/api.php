<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/**
 * Authentication Routes
 */
Route::group([], function (Router $Router) {
    $Router->group(["namespace" => "\Laravel\Passport\Http\Controllers"], function (Router $router) {
        $router->post('login', [
            'as' => 'auth.login',
            'middleware' => ['throttle'],
            'uses' => 'AccessTokenController@issueToken',
        ]);
    });

    $Router->post('register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register',
    ]);

    $Router->post('register-verify', [
        'as' => 'auth.register.verify',
        'uses' => 'AuthController@register_verify',
    ]);

    $Router->post('register-verify-resend', [
        'as' => 'auth.register.verify.resend',
        'uses' => 'AuthController@register_verify_resend',
    ]);
});

/**
 * User Routes
 */
Route::group(["middleware" => "auth:api"], function (Router $Router) {
    $Router->post('change-email', [
        'as' => 'user.change.email',
        'uses' => 'UserController@changeEmail',
    ]);

    $Router->post('change-email-submit', [
        'as' => 'user.change.email.submit',
        'uses' => 'UserController@changeEmailSubmit'
    ]);

    $Router->match(['post', 'put'], 'change-password', [
        'as' => 'user.change.password',
        'uses' => 'UserController@changePassword',
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

/**
 * Video Routes
 */
Route::group(["middleware" => "auth:api", 'prefix' => '/video'], function (Router $router) {
    $router->get('/', [
        'as' => 'video.list',
        'uses' => 'VideoController@list'
    ]);

    $router->post('/upload', [
        'as' => 'video.upload.video',
        'uses' => 'VideoController@upload'
    ]);

    $router->post('/upload-banner', [
        'as' => 'video.upload.banner',
        'uses' => 'VideoController@uploadBanner'
    ]);

    $router->post('/', [
        'as' => 'video.submit',
        'uses' => 'VideoController@create'
    ]);

    $router->put('/{video}/state', [
        'as' => 'video.setState',
        'uses' => 'VideoController@setState'
    ]);

});

/**
 * Category Routes
 */
Route::group(["middleware" => "auth:api", 'prefix' => '/category'], function (Router $Router) {
    $Router->get('/', [
        'as' => 'category.all',
        'uses' => 'CategoryController@all'
    ]);

    $Router->get('/my-categories', [
        'as' => 'category.my',
        'uses' => 'CategoryController@myCategories'
    ]);

    $Router->post('/upload-banner', [
        'as' => 'category.upload.banner',
        'uses' => 'CategoryController@uploadBanner'
    ]);

    $Router->post('/', [
        'as' => 'category.create',
        'uses' => 'CategoryController@create'
    ]);
});

/**
 * Playlist Routes
 */
Route::group(["middleware" => "auth:api", 'prefix' => '/playlist'], function (Router $Router) {
    $Router->get('/', [
        'as' => 'playlist.all',
        'uses' => 'PlaylistController@all'
    ]);
    $Router->get('/my-playlists', [
        'as' => 'playlist.my',
        'uses' => 'PlaylistController@myPlaylists'
    ]);
    $Router->post('/create', [
        'as' => 'playlist.create',
        'uses' => 'PlaylistController@create'
    ]);
});

/**
 * Tag Routes
 */
Route::group(["middleware" => "auth:api", 'prefix' => '/tag'], function (Router $Router) {
    $Router->get('/', [
        'as' => 'tag.all',
        'uses' => 'TagController@all'
    ]);
    $Router->post('/create', [
        'as' => 'tag.create',
        'uses' => 'TagController@create'
    ]);
});
