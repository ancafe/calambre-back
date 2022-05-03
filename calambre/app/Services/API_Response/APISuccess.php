<?php

namespace App\Services\API_Response;

use Symfony\Component\HttpFoundation\Response;

class APISuccess extends APIResponse
{

    public function __construct(string $msg)
    {
        parent::__construct(
            $msg,
            Response::HTTP_OK,
            true,
            []
        );
    }
}
