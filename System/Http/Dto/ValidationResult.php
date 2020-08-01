<?php


namespace System\Http\Dto;


use Ds\Vector;

class ValidationResult
{
    private bool $isSuccess = true;

    private string $error;

    private function __construct(bool $isSuccess, string $error = '')
    {
        $this->error = $error;
        $this->isSuccess=$isSuccess;
    }

    public static function createWithError(string $error): self
    {
        return new self(false, $error);
    }

    public static function createSuccess(): self
    {
        return new self(true);
    }


    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }


    public function getError(): string
    {
        return $this->error;
    }



}