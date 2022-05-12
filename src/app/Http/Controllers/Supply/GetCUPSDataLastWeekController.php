<?php

namespace App\Http\Controllers\Supply;

use App\Exceptions\Type\ApiError;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use DateTime;
use Illuminate\Http\JsonResponse;

class GetCUPSDataLastWeekController extends AbstractControllerReturnDataForChartsController
{

    /**
     * @throws ApiError
     */
    public function __invoke(Supply $supply): JsonResponse
    {
        $from = (new \DateTime)->setTimestamp(strtotime("last week monday"));
        $to = (new \DateTime)->setTimestamp(strtotime("next sunday", strtotime("last week monday")));

        return response()->json(new APISuccess($this->CUPSDataService->bar($supply, $from, $to)));
        //return response()->json(new APISuccess($this->CUPSDataService->get($supply, $from, $to)));
    }
}
