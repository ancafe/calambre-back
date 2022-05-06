<?php

namespace App\Http\Controllers\Supply;

use App\Http\Controllers\Controller;
use App\Models\Measure;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use App\Services\ValidateDateInterval;

class GetCUPSDataIntervalController extends Controller
{

    protected ValidateDateInterval $validateDateInterval;

    public function __construct(ValidateDateInterval $validateDateInterval)
    {
        $this->validateDateInterval = $validateDateInterval;
    }


    public function __invoke(Supply $supply, string $start, string $end)
    {
        $from = $start ? date_create_from_format("Y-m-d", $start) : null;
        $to = $end ? date_create_from_format("Y-m-d H:i:s", $end . " 23:59:59") : null;

        $this->validateDateInterval->validate($from, $to, 366);

        $measures = Measure::where('supply', $supply->id)
            ->whereBetween('date', [$from->format("Y-m-d"), $to->format("Y-m-d")])
            ->orderBy("startAt", "ASC")
            ->orderBy("hour", "ASC")
            ->get();

        $response = [
            'avg' => round(collect($measures)->avg('value'), 3),
            'max' => round(collect($measures)->max('value'), 3),
            'min' => round(collect($measures)->min('value'), 3),
            'total' => round(collect($measures)->sum('value'), 3),
            'data' => $measures,
        ];


        return response()->json(new APISuccess($response, [$start, $end]));
    }
}
