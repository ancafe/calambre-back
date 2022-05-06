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
    public function __invoke(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Encrypt email with fix IV to get always the same chain
        if (array_key_exists('email', $credentials)) {
            $credentials['email'] = $this->fixEncrypter->encryptString($credentials['email']);
        }

        //We add manually a new claims into payload for saving the given encrypt key
        try {
            if (! $token = auth()->claims(['encrypt_key' => $request->get('encrypt_key')])->attempt($credentials)) {
                throw new ApiUnauthorizedError([ErrorDtoFactory::invalidCredentials()]);
            }
        } catch (JWTException $e) {
            throw new ApiInternalError([ErrorDtoFactory::couldNotCreateToken()]);
        }

        return response()->json(new APISuccess(compact('token')));

    }
}
