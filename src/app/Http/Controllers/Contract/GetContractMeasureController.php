<?php

namespace App\Http\Controllers\Contract;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Edis\AbstractEdisController;
use App\Jobs\ReadMeasureFromEDISAndStore;
use App\Models\Contract;
use App\Models\Supply;
use App\Services\Edis\EdisService;
use App\Services\FixEncrypter;
use App\Services\Measure\StorageMeasureService;
use App\Services\SplitDates;
use Cassandra\Date;

class GetContractMeasureController extends AbstractEdisController
{
    protected StorageMeasureService $measureService;
    protected SplitDates $splitDates;

    public function __construct(
        EdisService           $edisService,
        FixEncrypter          $fixEncrypter,
        StorageMeasureService $measureService,
        SplitDates            $splitDates,

    )
    {
        $this->measureService = $measureService;
        $this->splitDates = $splitDates;
        parent::__construct($edisService, $fixEncrypter);
    }

    /**
     * @throws ApiError
     * @throws \Exception
     */
    public function __invoke(?Contract $contract, ?string $startDate = null, ?string $endDate = null)
    {
        if (!$this->edisService->check()) {
            throw new ApiError([ErrorDtoFactory::loginEdisError()]);
        }

        //If no contract was defined, so we use the defined as default
        if (!$contract->exists) {
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
            ])->first();

            if (!$contract || !$contract->exists) {
                throw new ApiError([ErrorDtoFactory::noActiveContractFounded()]);
            }
            $getSupply = $supply->first();
        } else {
            $getSupply = $contract->supply;
        }


        $intervals = $this->splitDates->toArray(
            date_create_from_format("Y-m-d", $startDate),
            date_create_from_format("Y-m-d", $endDate)
        );
        foreach ($intervals as $interval) {
            ReadMeasureFromEDISAndStore::dispatch(auth()->user(), $contract, $getSupply, $interval['start'], $interval['end']);
        }

        return response()->json(count($intervals) . ' job/s sended to queue');

    }
}
