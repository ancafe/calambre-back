<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Services\API_Response\APISuccess;

class EdisCheckLoginController extends AbstractEdisController
{

    /**
     * @throws ApiError
     * @throws \Exception
     */
    public function __invoke()
    {
        if (!$this->edisService->check()){
            throw new ApiError([ErrorDtoFactory::loginEdisError()]);
        }

        return response()->json(new APISuccess('Connected to Edis!'));
    }
}
