<?php

namespace App\Http\Controllers\User;


use App\Exceptions\Type\ApiError;
use App\Exceptions\Type\ApiNotFoundError;
use App\Exceptions\ErrorDtoFactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Edistribucion\EdisError;
use Illuminate\Http\Request;
use Edistribucion\EdisClient;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

}
