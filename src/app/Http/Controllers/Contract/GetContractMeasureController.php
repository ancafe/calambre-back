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
use Cassandra\Date;

class GetContractMeasureController extends AbstractEdisController
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

        $measures = null;
        $interval = true;

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        // no date defined. We read the measures from yesterday
        if (!$startDate && !$endDate) {
            $interval = false;
        }

        // only startDate defined. We read the measure for that specific date
        if ($startDate && !$endDate) {
            $endDate = clone $startDate;
        }

        // startDate and endDate defined. We read the measure for that interval
        if ($startDate && $endDate) {

            $diff = (int)$startDate->diff($endDate)->format("%r%a");

            if ($diff < 0) {
                throw new ApiError([ErrorDtoFactory::intervalMalformed()]);
            }

            if ($diff > 60) {
                throw new ApiError([ErrorDtoFactory::intervalOutOfRange()]);
            }
        }

        if (!$startDate && $endDate) {
            throw new ApiError([ErrorDtoFactory::undefined()]);
        }

        ReadMeasureFromEDISAndStore::dispatch(auth()->user(), $contract, $getSupply, $startDate, $endDate, $interval);
        return response()->json('Jobs sended to queue');

    }
}
