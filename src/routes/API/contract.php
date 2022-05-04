<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EDIS Routes
|--------------------------------------------------------------------------
*/

Route::get('{contract}/measure', \App\Http\Controllers\Contract\GetContractMeasureController::class);
Route::get('/measure', \App\Http\Controllers\Contract\GetDefaultMeasureController::class);

