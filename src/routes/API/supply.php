<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EDIS Routes
|--------------------------------------------------------------------------
*/

Route::get('/all', \App\Http\Controllers\Supply\GetAllSuppliesForUserController::class);
Route::put('{supply}/main', \App\Http\Controllers\Supply\SetAsMainSupplyController::class);



