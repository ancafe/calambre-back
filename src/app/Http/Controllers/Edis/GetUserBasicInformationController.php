<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\API_Response\APISuccess;
use App\Services\Edis\EdisService;

class GetUserBasicInformationController extends AbstractEdisController
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

        $info = $this->edisService->getLoginInfo();

        auth()->user()->edis->visibility = $info['visibility']['Id'];
        auth()->user()->edis->name = $info['Name'];
        auth()->user()->edis->saveOrFail();

        return response()->json(new APISuccess($this->edisService->getLoginInfo()));

    }
}
