<?php

use Illuminate\Support\Facades\Route\API;

/*
|--------------------------------------------------------------------------
| EDIS Routes
|--------------------------------------------------------------------------
|
*/
Route::get('check', \App\Http\Controllers\Edis\EdisCheckLoginController::class);
Route::put('set', \App\Http\Controllers\Edis\SetLoginInformationController::class);
Route::get('me', \App\Http\Controllers\Edis\GetUserBasicInformationController::class);
Route::get('supplies', \App\Http\Controllers\Edis\GetAndStoreSuppliesController::class);


