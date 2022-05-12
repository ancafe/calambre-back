<?php

namespace App\Http\Controllers\Supply;

use App\Exceptions\Type\ApiError;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use Illuminate\Http\JsonResponse;

class GetCUPSDataIntervalController extends AbstractControllerReturnDataForChartsController
{

    /**
     * @throws ApiError
     */
    public function __invoke(Supply $supply, string $start, string $end): JsonResponse
    {
        $from = $start ? date_create_from_format("Y-m-d", $start) : null;
        $to = $end ? date_create_from_format("Y-m-d H:i:s", $end . " 23:59:59") : null;

        return response()->json(new APISuccess($this->CUPSDataService->get($supply, $from, $to), [$start, $end]));

    }
}
