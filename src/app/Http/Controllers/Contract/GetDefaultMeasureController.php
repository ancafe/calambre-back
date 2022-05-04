<?php

namespace App\Http\Controllers\Contract;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Edis\AbstractEdisController;
use App\Models\Contract;
use App\Models\Supply;
use App\Services\Edis\EdisService;
use App\Services\FixEncrypter;
use App\Services\Measure\StorageMeasureService;

class GetDefaultMeasureController extends AbstractEdisController
{
    protected StorageMeasureService $measureService;

    public function __construct(EdisService $edisService, FixEncrypter $fixEncrypter, StorageMeasureService $measureService)
    {
        $this->measureService = $measureService;
        parent::__construct($edisService, $fixEncrypter);
    }

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

        $measures = $this->edisService->getMeasure($contract->first()->atrId);
        $this->measureService->storage($measures, $supply->first());


        return response()->json($measures);
    }
}
