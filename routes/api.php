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

Route::post('/auth/{provider}', ['as' => 'authenticate', 'uses' => 'Auth\AuthController@postAuthenticate']);

Route::post('user/store', ['as' => 'user_store', 'uses' => 'Auth\UserController@store']);

Route::post('professional/store', ['as' => 'professional_store', 'uses' => 'Auth\ProfessionalController@store']);