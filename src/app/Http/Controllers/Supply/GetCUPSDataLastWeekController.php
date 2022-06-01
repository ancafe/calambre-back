<?php

namespace App\Http\Controllers\Supply;

use App\Exceptions\Type\ApiError;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;

class GetCUPSDataLastWeekController extends AbstractControllerReturnDataForChartsController
{

    /**
     * @throws ApiError
     */
    public function __invoke(Supply $supply): JsonResponse
    {
        $last_data = new Carbon($supply->getLastDataAttribute());
        $last_data->setISODate($last_data->year,$last_data->weekOfYear);
        $from = $last_data->copy()->startOfWeek();
        $to = $last_data->copy()->endOfWeek();

        return response()->json(new APISuccess($this->CUPSDataService->bar($supply, $from, $to)));
        //return response()->json(new APISuccess($this->CUPSDataService->get($supply, $from, $to)));
    }
}
