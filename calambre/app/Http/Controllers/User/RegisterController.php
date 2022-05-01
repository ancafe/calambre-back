<?php

namespace App\Http\Controllers\User;


use App\Exceptions\GenericAPIError;
use App\Exceptions\JsonEncodeException;
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

        $username = $fields['name'];
        $password = $fields['password'];

        $edis = new EdisClient($username, $password);
        try {
            $login = $edis->login();
        } catch (EdisError $e) {
            die("error en login");
        }

        if ($login) {
            die("he hecho login");
        } else {
            die("no login");
        }


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
