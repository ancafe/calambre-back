<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EDIS Routes
|--------------------------------------------------------------------------
*/

Route::get('/all', \App\Http\Controllers\Supply\GetAllSuppliesForUserController::class);
Route::put('{supply}/main', \App\Http\Controllers\Supply\SetAsMainSupplyController::class);


Route::get('/{supply}/data/{startDate}/{endDate}', \App\Http\Controllers\Supply\GetCUPSDataIntervalController::class);
Route::get('/{supply}/data/{startDate}', \App\Http\Controllers\Supply\GetCUPSDataSingleDayController::class);

Route::get('/{supply}/data/lastWeek/', \App\Http\Controllers\Supply\GetCUPSDataLastWeekController::class);
Route::get('/{supply}/data/week/', \App\Http\Controllers\Supply\GetCUPSDataCurrentWeekController::class);
Route::get('/{supply}/data/months/', \App\Http\Controllers\Supply\GetCUPSDataByMonthsController::class);
Route::get('/{supply}/data/months/{year}', \App\Http\Controllers\Supply\GetCUPSDataByMonthsController::class);

