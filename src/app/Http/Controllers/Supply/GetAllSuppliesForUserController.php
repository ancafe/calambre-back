<?php

namespace App\Http\Controllers\Supply;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;
use Illuminate\Support\Facades\Log;

class GetAllSuppliesForUserController extends Controller
{

    public function __invoke(Supply $supply)
    {
        return response()->json(new APISuccess(Supply::with('contracts')->get()->toArray()));

    }
}
