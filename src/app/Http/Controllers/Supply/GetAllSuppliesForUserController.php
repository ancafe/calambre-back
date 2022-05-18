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

    /**
     * @OA\Get(
     *     path="/api/supply/all",
     *     summary="Get all supplies from user",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function __invoke(Supply $supply)
    {
        return response()->json(new APISuccess(Supply::with('contracts')->get()->toArray()));

    }
}
