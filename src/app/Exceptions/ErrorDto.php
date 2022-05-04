<?php

namespace App\Exceptions;

class ErrorDto
{
    private string $code;
    private string $description;
    private ?array $variables;

    /**
     * ErrorDto constructor.
     * @param string $code
     * @param string $description
     * @param array|null $variables
     */
    public function __construct(string $code, string $description, array $variables = null)
    {
        $this->code = $code;
        $this->description = $description;
        $this->variables = $variables;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $returnArray = [
            'code' => $this->code,
            'description' => $this->description,
        ];

        if (!empty($this->variables)) {
            $returnArray[] = $this->variables;
        }

        return json_encode($returnArray);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array|null
     */
    public function getVariables(): ?array
    {
        return $this->variables;
    }
}
