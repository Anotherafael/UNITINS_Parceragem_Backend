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

Route::prefix('features')->group(function () {
    Route::post('add-professions/{id}', ['as' => 'add_professions', 'uses' => 'Features\ProfessionalProfessionsController@store']);
});

Route::prefix('transaction')->group(function () {
    Route::post('add-order', ['as' => 'add_order', 'uses' => 'Transaction\OrderController@store']);
});

