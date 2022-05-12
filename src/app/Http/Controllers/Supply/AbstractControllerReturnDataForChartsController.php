<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Services\Measure\GetCUPSDataService;

abstract class AbstractControllerReturnDataForChartsController extends Controller
{

    protected GetCUPSDataService $CUPSDataService;

    public function __construct(GetCUPSDataService $CUPSDataService)
    {
        $this->CUPSDataService = $CUPSDataService;
    }

}
