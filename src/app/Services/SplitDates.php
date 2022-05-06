<?php

namespace App\Services;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;

class SplitDates
{

    protected ValidateDateInterval $validateDateInterval;

    public function __construct(ValidateDateInterval $validateDateInterval)
    {
        $this->validateDateInterval = $validateDateInterval;
    }

    public function toArray(?\DateTime $start, ?\DateTime $end) : array
    {
        $array = [];
        $daysByGroup = 60; //max days interval in Edis

        $this->validateDateInterval->validate($start, $end);

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
        } while ($nEnd < $end);


        return $array;

    }
}
