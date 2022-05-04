<?php

namespace App\Services\API_Response;

use Symfony\Component\HttpFoundation\Response;

class APIResponse
{

    public int $code;
    public bool $success;
    public string $msg;
    public array $variables;


    public function __construct(
        string $msg,
        int $code = Response::HTTP_OK,
        bool $success = true,
        array $variables = []
    )
    {
        $this->code = $code;
        $this->success = $success;
        $this->msg = $msg;
        $this->variables = $variables;

    }

}
