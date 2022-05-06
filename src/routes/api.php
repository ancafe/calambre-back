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


Route::withoutMiddleware('auth:api')->group(function () {
    Route::post('register', \App\Http\Controllers\User\RegisterController::class);
    Route::post('login', [\App\Http\Controllers\User\LoginController::class, 'login']);
    Route::post('refresh', [\App\Http\Controllers\User\LoginController::class, 'refresh'])->withoutMiddleware(['auth:api']);

});

Route::group(['middleware' => ['jwt.verify', 'RLS']], function () {
    Route::get('me', \App\Http\Controllers\User\GetUserInformationController::class);
    Route::post('logout', \App\Http\Controllers\User\LogoutController::class);
    Route::prefix('/edis')->group(__DIR__ . '/API/edis.php');
    Route::prefix('/supply')->group(__DIR__ . '/API/supply.php');
    Route::prefix('/contract')->group(__DIR__ . '/API/contract.php');
});

