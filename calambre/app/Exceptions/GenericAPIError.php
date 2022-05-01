<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenericAPIError extends JsonEncodeException
{
    public function render(Request $request): Response
    {
        $status = 400;
        $error = "Something is wrong";
        $help = "Contact the sales team to verify";
        return response([
            "status" => $status,
            "code" => 0,
            "error" => $error,
            "help" => $help
        ], $status);
    }
}
