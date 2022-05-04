<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EDIS Routes
|--------------------------------------------------------------------------
*/

Route::put('{supply}/main', \App\Http\Controllers\Supply\SetAsMainSupplyController::class);




