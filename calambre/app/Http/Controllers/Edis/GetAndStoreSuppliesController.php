<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Supply;
use Illuminate\Support\Facades\Log;

class GetAndStoreSuppliesController extends AbstractEdisController
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

        $supplies = $this->edisService->getSupplies();
        foreach ($supplies['data']['lstCups'] as $supply) {
            $dbSupply = Supply::create([
                'user' => auth()->user()->id,
                'edis_id' => $supply['Id'],
                'cups' => $supply['Name'],
                'provisioning_address' =>  $supply['Provisioning_address__c'],
            ]);
            Log::info("New supply added to user " . auth()->user()->id . " with CUPS " . $dbSupply->cups);
        }


    }
}
