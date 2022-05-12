<?php

namespace App\Services\Measure;

use App\Exceptions\Type\ApiError;
use App\Models\Measure;
use App\Models\Supply;
use App\Services\ValidateDateInterval;
use Illuminate\Support\Facades\DB;

class GetCUPSDataService
{

    protected ValidateDateInterval $validateDateInterval;

    public function __construct(ValidateDateInterval $validateDateInterval)
    {
        $this->validateDateInterval = $validateDateInterval;
    }

    /**
     * @throws ApiError
     */
    public function get(Supply $supply, \DateTime $from, \DateTime $to): array
    {
        $this->validateDateInterval->validate($from, $to, 366);

        $measures = Measure::select()->where('supply', $supply->id)
            ->whereBetween('date', [$from->format("Y-m-d"), $to->format("Y-m-d")])
            ->orderBy("startAt", "ASC")
            ->orderBy("hour", "ASC")
            ->get();


        return [
            'from' => $from->format("Y-m-d"),
            'to' => $to->format("Y-m-d"),
            'avg' => round(collect($measures)->avg('value'), 3),
            'max' => round(collect($measures)->max('value'), 3),
            'min' => round(collect($measures)->min('value'), 3),
            'total' => round(collect($measures)->sum('value'), 3),
            'data' => $measures,
        ];


    }


    public function bar(Supply $supply, \DateTime $from, \DateTime $to): array
    {
        $this->validateDateInterval->validate($from, $to, 366);

        $measures = DB::table("measures")
            ->select("measures.date", "hour_period.period_code", DB::raw("sum(measures.value) as total"))
            ->where('supply', $supply->id)
            ->leftJoin("hour_period", function ($join) {
                $join->on('hour_period.hour_id', "=", "measures.hournum")
                    ->where('hour_period.weekday', "=", DB::raw("extract(dow from date::timestamp)"))
                    ->where('hour_period.holiday', "=", false);
            })
            ->whereBetween('date', [$from->format("Y-m-d"), $to->format("Y-m-d")])
            ->groupBy("hour_period.period_code")
            ->groupBy("measures.date")
            ->orderBy("measures.date", "ASC")
            ->orderBy("hour_period.period_code", "ASC")
            ->get();


        $uniqueDays = [];
        foreach($measures as $measure)
        {
            if (!in_array($measure->date, $uniqueDays)) {
                $uniqueDays[] = $measure->date;
            }
        }

        $uniquePeriods = [];
        foreach($measures as $measure)
        {
            if (!in_array($measure->period_code, $uniquePeriods)) {
                $uniquePeriods[] = $measure->period_code;
            }
        }




        return [
            'from' => $from->format("Y-m-d"),
            'to' => $to->format("Y-m-d"),
            'avg' => round(collect($measures)->avg('value'), 3),
            'max' => round(collect($measures)->max('value'), 3),
            'min' => round(collect($measures)->min('value'), 3),
            'total' => round(collect($measures)->sum('value'), 3),
            'data' => $measures,
        ];


    }
}




