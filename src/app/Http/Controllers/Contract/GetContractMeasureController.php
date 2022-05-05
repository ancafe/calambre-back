<?php

namespace App\Http\Controllers\Contract;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Edis\AbstractEdisController;
use App\Jobs\ReadMeasureFromEDISAndStore;
use App\Models\Contract;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use App\Services\Edis\EdisService;
use App\Services\FixEncrypter;
use App\Services\Measure\StorageMeasureService;
use App\Services\SplitDates;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

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
            $getSupply = $contract->belongsToSupply;
        }


        $startInterval = $startDate ? date_create_from_format("Y-m-d", $startDate) : null;
        $endInterval = $endDate ? date_create_from_format("Y-m-d", $endDate) : null;

        if (!$startDate && !$endDate) {
            $intervals = [[
                'start' => null,
                'end' => null,
            ]];
        } else {
            $intervals = $this->splitDates->toArray($startInterval, $endInterval);
        }


        $batchClasses = [];
        foreach ($intervals as $interval) {
            $batchClasses[] = new ReadMeasureFromEDISAndStore(auth()->user(), $contract, $getSupply, $interval['start'], $interval['end']);
        }

        $batch = Bus::batch($batchClasses)
            ->then(function (Batch $batch) {
                // All jobs completed successfully...
                Log::info("Batch " . $batch->id . " executed successful");
            })->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected...
                Log::error("Error during execute batch " . $batch->id);
            })->finally(function (Batch $batch) {
                // The batch has finished executing...
            })
            ->name("Import measure: U: " . auth()->user()->email . " [" . $startDate . " - " . $endDate . "]")
            ->dispatch();


        Log::info('Batch with id ' . $batch->id . ' with ' . count($intervals) . ' jobs sent to queue. It will execute in few seconds...');
        return response()->json(new APISuccess('Batch with id ' . $batch->id . ' with ' . count($intervals) . ' jobs sent to queue. It will execute in few seconds...'));

    }
}
