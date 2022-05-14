<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Models\Measure;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use App\Services\ValidateDateInterval;
use Illuminate\Support\Carbon;

class GetCUPSDataByMonthsController extends AbstractControllerReturnDataForChartsController
{

    public function __invoke(Supply $supply, ?int $year = null)
    {

        if (!$year) {
            $start = Carbon::now()->year . "-01-01";
            $end = Carbon::now()->format("Y-m-d");
        } else {
            $start = Carbon::create($year, 1, 1)->format("Y-m-d");
            $end = Carbon::create($year, 12, 31)->format("Y-m-d");
        }


        $from = $start ? date_create_from_format("Y-m-d", $start) : null;
        $to = $end ? date_create_from_format("Y-m-d H:i:s", $end . " 23:59:59") : null;

        return response()->json(new APISuccess($this->CUPSDataService->monthly($supply, $from, $to), [$start, $end]));

    }
}
