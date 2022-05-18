<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CreateDatabaseUserWhenUserRegisteredService;
use App\Services\FixEncrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class RegisterController extends Controller
{
    private FixEncrypter $fixEncrypter;

    public function __construct(FixEncrypter $fixEncrypter)
    {
        $this->fixEncrypter = $fixEncrypter;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register new user",
     *     @OA\Parameter(
     *          name="body",
     *          in="query",
     *          description="JSON Payload for user register",
     *          required=true,
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="user@user.com"),
     *              @OA\Property(property="name", type="string", example="My Name"),
     *              @OA\Property(property="password", type="string", example="***********"),
     *              @OA\Property(property="password_confirmation", type="string", example=""),
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     * @throws ApiError
     */
    public function __invoke(Request $request, CreateDatabaseUserWhenUserRegisteredService $createDatabaseUserWhenUserRegisteredService)
    {
        Log::info($request);

        $request->request->add(['email_encrypted' => $this->fixEncrypter->encryptString($request->get('email'))]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'email_encrypted' => 'unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            throw new ApiError([ErrorDtoFactory::validation($validator->errors()->toArray())]);
        }

        $password = Hash::make($request->get('password'));

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $password,
        ]);

        $token = JWTAuth::fromUser($user);

        $createDatabaseUserWhenUserRegisteredService->create($user->id, $password);
        Log::info("Registered user $user->id with email $user->email");

        //return response()->json(new APISuccess(compact('token')));

        return response()->json(compact('user','token'),201);
    }
}
