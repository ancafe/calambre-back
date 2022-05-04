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

        } catch (\Exception $e) {
            throw new ApiError([ErrorDtoFactory::undefined()]);
        }

        Log::info("Main supply for user has changed");
        return response()->json(new APISuccess("Your main supply now it's $supply->cups with address $supply->provisioning_address"));
    }
}
