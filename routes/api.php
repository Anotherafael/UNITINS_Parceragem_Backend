<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/{provider}', ['as' => 'authenticate', 'uses' => 'Auth\AuthController@postAuthenticate']);
    Route::post('register/{provider}', ['as' => 'user_register', 'uses' => 'Auth\UserController@store']);
});

Route::post('forgot-password', ['as' => 'forgot_password', 'uses' => 'Auth\NewPasswordController@forgotPassword']);
Route::post('reset-password', ['as' => 'reset_password', 'uses' => 'Auth\NewPasswordController@reset']);

Route::prefix('features')->group(function () {
    Route::post('add-professions', ['as' => 'add_professions', 'uses' => 'Features\ProfessionalProfessionsController@store']);
});

Route::prefix('transaction')->group(function () {
    Route::post('add-order', ['as' => 'add_order', 'uses' => 'Transaction\OrderController@store']);
    Route::post('request-order', ['as' => 'request_order', 'uses' => 'Transaction\RequestOrderController@store']);
    
    Route::group(['prefix' => 'order'], function () {
        Route::post('accept', ['as' => 'accept_order', 'uses' => 'Transaction\StatusController@accept']);
        Route::post('reject', ['as' => 'reject_order', 'uses' => 'Transaction\StatusController@reject']);
    });
});