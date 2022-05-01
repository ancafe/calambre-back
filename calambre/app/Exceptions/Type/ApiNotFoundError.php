<?php

namespace App\Exceptions\Type;

use Symfony\Component\HttpFoundation\Response;

class ApiNotFoundError extends ApiError
{
    public function __construct(array $errors)
    {
        parent::__construct($errors, Response::HTTP_NOT_FOUND);
    }
}
