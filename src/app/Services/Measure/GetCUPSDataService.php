<?php

namespace App\Services\Measure;

use App\Exceptions\Type\ApiError;
use App\Models\Measure;
use App\Models\Period;
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
            'data' => $this->get_in_bar_mode($measures),
        ];


    }

    private function filter_by_period($measures, $code)
    {
        return collect($measures)->filter(function ($item) use ($code) {
            if ($item->period == $code) {
                return true;
            }
        });
    }

    private function get_measures_in_chart_format($measures)
    {
        $data = [];
        foreach ($measures as $measure) {
            $data[] = [
                'x' => $measure->date,
                'y' => $measure->value,

            ];
        }
        return $data;
    }

    private function get_in_bar_mode($measures)
    {
        $data = [];
        $periods = Period::orderBy('code', 'DESC')->get();

        foreach ($periods as $period) {

            $filtered_by_period = $this->filter_by_period($measures, $period->code);
            $format_to_chart = $this->get_measures_in_chart_format($filtered_by_period);

            $group_by_date = collect($format_to_chart)->groupBy('x')->map(function ($row) {
                return $row->sum('y');
            });

            $temp = [];
            foreach ($group_by_date as $key => $value) {
                $temp[] = [
                    'x' => $key,
                    'y' => round($value,2),
                ];
            }
            $group_by_date = $temp;

            $data[] = [
                'name' => $period->code,
                'data' => $group_by_date
            ];
        }

        return $data;

    }
}




