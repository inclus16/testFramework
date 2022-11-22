<?php

namespace System\Http\Dto;

use Ds\Map;

class ValidationResult
{

    public function __construct(private readonly int $statusCode,
                                private readonly Map $errors)
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): Map
    {
        return $this->errors;
    }
}