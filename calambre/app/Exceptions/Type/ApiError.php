<?php

namespace App\Exceptions\Type;

use Symfony\Component\HttpFoundation\Response;

class ApiError extends \Exception
{
    /**
     * ApiError constructor.
     * @param ErrorDto[] $errors
     * @param int $code
     */
    public function __construct(array $errors, int $code = Response::HTTP_BAD_REQUEST)
    {

        parent::__construct(json_encode(array_map(
            function ($error) {
                $returnArray = [
                    'code' => $error->getCode(),
                    'success' => false,
                    'description' => $error->getDescription(),
                ];

                if ($error->getVariables()) {
                    $returnArray['variables'] = $error->getVariables();
                }

                return $returnArray;
            },
            $errors
        )), $code);
    }
}
