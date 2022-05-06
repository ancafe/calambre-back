<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Exceptions\Type\ApiNotFoundError;
use App\Http\Controllers\Controller;
use App\Services\API_Response\APISuccess;
use JWTAuth;

class GetUserInformationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get the autheticated user information",
     *     @OA\Response(response=200, description="Return a list of resources"),
     *     security={{ "apiAuth": {} }}
     * )
     *
     * @throws ApiError
     */
    public function __invoke()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                throw new ApiNotFoundError([ErrorDtoFactory::userNotFound()]);
            }
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e) {
            throw new ApiError([ErrorDtoFactory::tokenExpired()]);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException $e) {
            throw new ApiError([ErrorDtoFactory::tokenInvalid()]);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            throw new ApiError([ErrorDtoFactory::tokenAbsent()]);
        }

        return response()->json(new APISuccess(compact('user')));

    }
}
