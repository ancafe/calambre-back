<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EDIS Routes
|--------------------------------------------------------------------------
*/


//Measure default contract
Route::get('/measure', \App\Http\Controllers\Contract\GetContractMeasureController::class);

//Measure default contract, specific date
Route::get('/measure/{startDate}/', \App\Http\Controllers\Contract\GetContractMeasureController::class);

//Measure default contract, interval range
Route::get('/measure/{startDate}/{endDate}/', \App\Http\Controllers\Contract\GetContractMeasureController::class);


//Measure by contract
Route::get('{contract}/measure', \App\Http\Controllers\Contract\GetContractMeasureController::class);

//Measure by contract, specific date
Route::get('{contract}/measure/{startDate}/', \App\Http\Controllers\Contract\GetContractMeasureController::class);

//Measure default contract, interval range
Route::get('{contract}/measure/{startDate}/{endDate}/', \App\Http\Controllers\Contract\GetContractMeasureController::class);
