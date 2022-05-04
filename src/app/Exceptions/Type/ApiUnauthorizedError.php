<?php

namespace App\Exceptions\Type;

use Symfony\Component\HttpFoundation\Response;

class ApiUnauthorizedError extends ApiError
{
    public function __construct(array $errors)
    {
        parent::__construct($errors, Response::HTTP_UNAUTHORIZED);
    }
}
