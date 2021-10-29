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

Route::group(['prefix' => 'v1'], function () {

    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::post('auth/login/{provider}', ['as' => 'login', 'uses' => 'Auth\AuthController@login']);
    Route::post('auth/register/{provider}', ['as' => 'register', 'uses' => 'Auth\AuthController@register']);

    Route::post('forgot-password', ['as' => 'forgot_password', 'uses' => 'Auth\NewPasswordController@forgotPassword']);
    Route::post('reset-password', ['as' => 'reset_password', 'uses' => 'Auth\NewPasswordController@resetPassword']);

    Route::middleware(['auth:api'])->group(function () {

        Route::post('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);
        Route::get('me', ['as' => 'get_me', 'uses' => 'Auth\MeController@index']);
        Route::put('me/update', ['as' => 'update_me', 'uses' => 'Auth\MeController@update']);
        
        Route::prefix('service')->group(function () {
            Route::get('sections', ['as' => 'get_sections', 'uses' => 'Service\SectionController@index']);
            Route::get('professions', ['as' => 'get_professions', 'uses' => 'Service\ProfessionController@index']);
            Route::get('tasks', ['as' => 'get_tasks', 'uses' => 'Service\TaskController@index']);
        });
        
        Route::prefix('transaction')->group(function () {
            Route::apiResource('request-order', 'Transaction\RequestOrderController')->except('create', 'edit');
            Route::apiResource('order', 'Transaction\OrderController')->except('create', 'edit');
        });
    
        Route::prefix('features')->group(function () {
            Route::post('add-professions', ['as' => 'add_professions', 'uses' => 'Features\ProfessionalProfessionsController@addProfession']);
        });
    
        Route::group(['prefix' => 'order'], function () {
            Route::post('accept', ['as' => 'accept_order', 'uses' => 'Transaction\StatusController@accept']);
            Route::post('reject', ['as' => 'reject_order', 'uses' => 'Transaction\StatusController@reject']);
        });
    });
});
