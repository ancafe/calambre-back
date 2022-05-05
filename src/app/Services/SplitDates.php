<?php

namespace App\Services;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use Illuminate\Support\Facades\Date;

class SplitDates
{


    public function toArray(?\DateTime $start, ?\DateTime $end)
    {
        $array = [];
        $daysByGroup = 60; //max days interval in Edis
        $diff = (int)$start->diff($end)->format("%r%a");

        if ($diff < 0) {
            throw new ApiError([ErrorDtoFactory::intervalMalformed()]);
        }

        if (!$start && $end) {
            throw new ApiError([ErrorDtoFactory::undefined()]);
        }


        $i = 0;

        do {
            $nStart = clone $start;
            $nEnd = clone $start;
            date_add($nStart, date_interval_create_from_date_string(($i * $daysByGroup) . " days"));
            $tempEnd = clone $nEnd;
            date_add($tempEnd, date_interval_create_from_date_string(($i * $daysByGroup) + ($daysByGroup - 1) . " days"));
            $nEnd = ($tempEnd > $end) ? clone $end : clone $tempEnd;

            $array[] = [
                "start" => $nStart,
                "end" => $nEnd,
            ];

            $i++;
        } while ( $nEnd < $end);


        return $array;

    }
}
