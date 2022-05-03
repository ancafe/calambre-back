<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Services\API_Response\APISuccess;
use App\Services\Edis\CheckIfLoginIsSuccessService;

class EdisCheckLoginController extends Controller
{
    private CheckIfLoginIsSuccessService $checkIfLoginIsSuccessService;

    public function __construct(CheckIfLoginIsSuccessService $checkIfLoginIsSuccessService)
    {
        $this->checkIfLoginIsSuccessService = $checkIfLoginIsSuccessService;
    }

    /**
     * @throws ApiError
     */
    public function __invoke()
    {
        if (!$this->checkIfLoginIsSuccessService->check()){
            throw new ApiError([ErrorDtoFactory::loginEdisError()]);
        }

        return response()->json(new APISuccess('Connected to Edis!'));
    }
}
