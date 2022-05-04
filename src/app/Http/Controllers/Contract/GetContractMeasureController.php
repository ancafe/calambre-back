<?php

namespace App\Http\Controllers\Contract;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Edis\AbstractEdisController;
use App\Models\Contract;

class GetContractMeasureController extends AbstractEdisController
{
    /**
     * @throws ApiError
     * @throws \Exception
     */
    public function __invoke(Contract $contract)
    {
        if (!$this->edisService->check()){
            throw new ApiError([ErrorDtoFactory::loginEdisError()]);
        }

        return response()->json($this->edisService->getMeasure($contract->atrId));
    }
}
