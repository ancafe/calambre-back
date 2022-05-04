<?php

namespace App\Http\Controllers\Contract;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Edis\AbstractEdisController;
use App\Models\Contract;
use App\Models\Supply;

class GetDefaultMeasureController extends AbstractEdisController
{
    /**
     * @throws ApiError
     * @throws \Exception
     */
    public function __invoke()
    {
        if (!$this->edisService->check()) {
            throw new ApiError([ErrorDtoFactory::loginEdisError()]);
        }

        $supply = Supply::where([
            ["user", auth()->user()->id],
            ["main", true],
        ])->get();


        if (count($supply) == 0) {
            throw new ApiError([ErrorDtoFactory::noDefaultSupplyFounded()]);
        } elseif (count($supply) > 1) {
            throw new ApiError([ErrorDtoFactory::moreThanOneDefaultSupplyFounded()]);
        }

        $contract = Contract::where([
            ["supply", $supply->first()->id],
            ["status_code", 3],
        ])->get();

        if (count($contract) == 0) {
            throw new ApiError([ErrorDtoFactory::noActiveContractFounded()]);
        }

        return response()->json($this->edisService->getMeasure($contract->first()->atrId));
    }
}
