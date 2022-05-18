<?php

namespace App\Http\Controllers\Supply;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use Illuminate\Support\Facades\Log;

class SetAsMainSupplyController extends Controller
{

    /**
     * @OA\Put(
     *     path="/api/supply/{supply}/main",
     *     summary="Set the defined supply as the main supply",
     *     @OA\Parameter(
     *         description="UUID of a supply",
     *         in="path",
     *         name="supply",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     * @throws ApiError
     */
    public function __invoke(Supply $supply)
    {

        try {

            //Change the main supply to given in URL
            $supply->main = true;
            $supply->saveOrFail();

            //Change the others to false
            $supplies = Supply::all()->except($supply->id);
            foreach($supplies as $other){
                $other->main = false;
                $other->saveOrFail();
            }
        } catch (\Throwable $e) {
            throw new ApiError([ErrorDtoFactory::undefined()]);
        }

        Log::info("Main supply for user has changed");
        return response()->json(new APISuccess("Your main supply now it's $supply->cups with address $supply->provisioning_address"));
    }
}
