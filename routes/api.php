<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);

// Route::apiResource('/logout', 'Api')->middleware('auth:api');

Route::group([
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('/login', 'API\AuthController@login')->name('login.api');
    Route::post('/register', 'API\AuthController@register')->name('register.api');
    Route::get('/logout', 'API\AuthController@logout')->middleware('auth:api');
    Route::apiResource('/user', 'API\UserController')->middleware('auth:api');
});
