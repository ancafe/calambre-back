<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\RegisterController;
use Illuminate\Http\Request;
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


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'authenticate']);

Route::group(['middleware' => ['jwt.verify', 'RLS']], function () {

    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);

});
