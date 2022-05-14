<?php

namespace App\Services\Measure;

use App\Exceptions\Type\ApiError;
use App\Models\Measure;
use App\Models\Period;
use App\Models\Supply;
use App\Services\ValidateDateInterval;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetCUPSDataService
{

    protected ValidateDateInterval $validateDateInterval;

    public function __construct(ValidateDateInterval $validateDateInterval)
    {
        $this->validateDateInterval = $validateDateInterval;
    }

    public function getMeasure(Supply $supply, \DateTime $from, \DateTime $to): Collection
    {
        return Measure::select()->where('supply', $supply->id)
            ->whereBetween('date', [$from->format("Y-m-d"), $to->format("Y-m-d")])
            ->orderBy("startAt", "ASC")
            ->orderBy("hour", "ASC")
            ->get();
    }

    public function returnDTO(\Illuminate\Support\Collection|\Illuminate\Database\Eloquent\ºCollection $measures, \DateTime $from, \DateTime $to, $customData)
    {
        return [
            'from' => $from->format("Y-m-d"),
            'to' => $to->format("Y-m-d"),
            'avg' => round(collect($measures)->avg('value'), 3),
            'max' => round(collect($measures)->max('value'), 3),
            'min' => round(collect($measures)->min('value'), 3),
            'total' => round(collect($measures)->sum('value'), 3),
            'data' => $customData,
        ];
    }

    /**
     * @throws ApiError
     */
    public function get(Supply $supply, \DateTime $from, \DateTime $to): array
    {
        $this->validateDateInterval->validate($from, $to, 366);
        $measures = $this->getMeasure($supply, $from, $to);
        return $this->returnDTO($measures, $from, $to, $measures);

    }

    public function daily(Supply $supply, \DateTime $from, \DateTime $to): array
    {
        $this->validateDateInterval->validate($from, $to, 366);
        $measures = $this->getMeasure($supply, $from, $to);
        return $this->returnDTO($measures, $from, $to, $this->get_in_daily_mode($measures));

    }

    public function monthly(Supply $supply, \DateTime $from, \DateTime $to): array
    {
        $this->validateDateInterval->validate($from, $to, 366);
        $measures = DB::table("measures")
            ->select(DB::raw("DATE_PART('month', date) as month"))
            ->addSelect(DB::raw("sum(measures.value) as total"))
            ->where('supply', $supply->id)
            ->whereBetween('date', [$from->format("Y-m-d"), $to->format("Y-m-d")])
            ->groupBy(DB::raw("DATE_PART('month', date)"))
            ->get();

        return $this->returnDTO($measures, $from, $to, $this->get_in_monthly_mode($measures));

    }


    public function bar(Supply $supply, \DateTime $from, \DateTime $to): array
    {
        $this->validateDateInterval->validate($from, $to, 366);
        $measures = $this->getMeasure($supply, $from, $to);
        return $this->returnDTO($measures, $from, $to, $this->get_in_bar_mode($measures));

    }

    private function filter_by_period($measures, $code)
    {
        return collect($measures)->filter(function ($item) use ($code) {
            if ($item->period == $code) {
                return true;
            }
        });
    }

    private function get_measures_in_chart_format($measures): array
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

    private function get_in_bar_mode($measures): array
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
                    'y' => round($value, 2),
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

    private function get_in_daily_mode($measures): array
    {
        $data = [];


        $serie_consumption_data = [];
        foreach ($measures as $interval) {

            $serie_consumption_data[] = [
                'date' => $interval->date,
                'x' => $interval->hour,
                'y' => $interval->value,
                'fillColor' => $this->color($interval->period)
            ];
        }

        $data[] = [
            'name' => 'kWh',
            'data' => $serie_consumption_data
        ];

        return $data;
    }


    private function get_in_monthly_mode($measures): array
    {
        $data = [];


        $serie_consumption_data = [];
        foreach ($measures as $interval) {

            $serie_consumption_data[] = [
                'x' => date('F', strtotime("1900-$interval->month-01")) ,
                'y' => round($interval->total,3)
            ];
        }

        $data[] = [
            'name' => 'kWh',
            'data' => $serie_consumption_data
        ];

        return $data;
    }

    private function color(string $period): string
    {
        if ($period == "P1") {
            return Measure::P1COLOR;
        }

        if ($period == "P2") {
            return Measure::P2COLOR;
        }

        if ($period == "P3") {
            return Measure::P3COLOR;
        }

        return "#000000"; //black
    }
}




