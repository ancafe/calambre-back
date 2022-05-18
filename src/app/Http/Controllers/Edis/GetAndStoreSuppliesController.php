<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use App\Services\FixEncrypter;
use Edistribucion\EdisClient;
use Illuminate\Support\Facades\Log;

class GetAndStoreSuppliesController extends AbstractEdisController
{


    /**
     *
     * @OA\Get(
     *     path="/api/contract/{contract}/measure/{from}/{to}/",
     *     summary="Get measure interval from contract",
     *     @OA\Parameter(
     *         description="UUID of a contract",
     *         in="path",
     *         name="contract",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Date from",
     *         in="path",
     *         name="from",
     *         required=true,
     *         example="2021-01-01",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         description="Date to",
     *         in="path",
     *         name="to",
     *         required=true,
     *         example="2021-01-15",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @throws ApiError
     * @throws \Exception
     */
    public function __invoke()
    {
        $msg = [];

        if (!$this->edisService->check()) {
            throw new ApiError([ErrorDtoFactory::loginEdisError()]);
        }

        $supplies = $this->edisService->getSupplies();
        foreach ($supplies['data']['lstCups'] as $supply) {
            $dbSupply = Supply::updateOrCreate([
                'edis_id' => $this->fixEncrypter->encryptString($supply['Id']),
            ], [
                'user' => auth()->user()->id,
                'edis_id' => $supply['Id'],
                'cups' => $supply['Name'],
                'provisioning_address' => $supply['Provisioning_address__c'],
            ]);
            Log::info("New supply added to user " . auth()->user()->id . " with CUPS " . $dbSupply->cups);
            $msg[] = "New supply added to user " . auth()->user()->id . " with CUPS " . $dbSupply->cups;

            $detail = $this->edisService->getCUPSDetail($dbSupply->edis_id);

            foreach ($detail['lstATR'] as $contract) {

                $dbCompany = Company::updateOrCreate([
                    'companyId' => $this->fixEncrypter->encryptString($contract['ContractHolder__r']['Id']),
                ], [
                    'companyId' => $contract['ContractHolder__r']['Id'],
                    'name' => $contract['Comercializadora'],
                    'user' => null
                ]);

                Log::info("New company " . $dbCompany->name . " with internal ID " . $dbCompany->companyId);
                $msg[] = "New company " . $dbCompany->name . " with internal ID " . $dbCompany->companyId;


                $dbContract = Contract::updateOrCreate([
                    'atrId' => $this->fixEncrypter->encryptString($contract['Id']),
                ], [
                    'atrId' => $contract['Id'],
                    'atrNumContract' => $contract['Title'],
                    'status' => $contract['Status'],
                    'status_code' => (int)$contract['Status__c'],
                    'P1' => null,
                    'P2' => null,
                    'P3' => null,
                    'P4' => null,
                    'P5' => null,
                    'P6' => null,
                    'user' => auth()->user()->id,
                    'company' => $dbCompany->id,
                    'supply' => $dbSupply->id,
                ]);

                Log::info("New ATR contract added to supply " . $dbSupply->cups . " with number " . $dbContract->atrNumContract . " and internal ID " . $dbContract->atrId);
                $msg[] = "New ATR contract added to supply " . $dbSupply->cups . " with number " . $dbContract->atrNumContract . " and internal ID " . $dbContract->atrId;

            }
            return response()->json(new APISuccess($msg));

        }


    }
}
