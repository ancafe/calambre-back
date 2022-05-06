<?php

namespace App\Services;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use Illuminate\Support\Facades\Date;

class ValidateDateInterval
{

    public function validate(\DateTime $start, ?\DateTime $end, ?int $maxDays = 0): bool
    {

        if (!$end) {
            $end = clone $start;
        }

        $diff = (int) $start->diff($end)->format("%r%a");


        if ($diff < 0) {
            throw new ApiError([ErrorDtoFactory::intervalMalformed()]);
        }

        if ($maxDays > 0 && $diff > $maxDays) {
            throw new ApiError([ErrorDtoFactory::intervalOutOfRange(['max_days' => $maxDays])]);
        }

        return true;
    }

}
