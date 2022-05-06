<?php

namespace App\Services\API_Response;

use Symfony\Component\HttpFoundation\Response;

class APISuccess extends APIResponse
{

    public function __construct(string|array $msg, ?array $vars = [])
    {
        parent::__construct(
            $msg,
            Response::HTTP_OK,
            true,
            $vars
        );
    }
}
