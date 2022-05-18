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

class LoginController extends Controller
{

    private FixEncrypter $fixEncrypter;

    public function __construct(FixEncrypter $fixEncrypter)
    {
        $this->fixEncrypter = $fixEncrypter;
    }

    /**
     *
     * @OA\Post (
     *     path="/api/login",
     *     summary="New Make a login using email and password",
     *     @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="string"),
     *           ),
     *       )
     *     ),
     *     @OA\Response(response=200, description="Get a valid token"),
     * )
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiUnauthorizedError
     * @throws ApiInternalError
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Encrypt email with fix IV to get always the same chain
        if (array_key_exists('email', $credentials)) {
            $credentials['email'] = $this->fixEncrypter->encryptString($credentials['email']);
        }

        try {
            if (! $token = auth()->attempt($credentials)) {
                throw new ApiUnauthorizedError([ErrorDtoFactory::invalidCredentials()]);
            }
        } catch (JWTException $e) {
            throw new ApiInternalError([ErrorDtoFactory::couldNotCreateToken()]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }



    /**
     *
     * @OA\Post (
     *     path="/api/refresh",
     *     summary="Refresh token",
     *     @OA\Response(response=200, description="Get a new valid token"),
     * )
     *
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        return $this->respondWithToken(auth()->refresh());
    }

}
