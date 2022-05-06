<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiInternalError;
use App\Exceptions\Type\ApiUnauthorizedError;
use App\Http\Controllers\Controller;
use App\Services\API_Response\APISuccess;
use App\Services\FixEncrypter;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{

    /**
     *
     * @OA\Post (
     *     path="/api/logout",
     *     summary="Logout user",
     *     @OA\Response(response=200, description="Successfully logged out"),
     * )
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function __invoke(Request $request)
    {
        auth()->logout();
        return response()->json(new APISuccess("Successfully logged out"));

    }
}
