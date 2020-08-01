<?php


namespace System\Http\Dto;


class Rule
{
    private string $class;

    private array $parameters;

    private string $errorMessage;

    public function __construct(string $class, string $errorMessage = '', array $parameters = [])
    {
        $this->class = $class;
        $this->parameters = $parameters;
        $this->errorMessage = $errorMessage;
    }

    public function isDefaultMessage(): bool
    {
        return empty($this->errorMessage);
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getClass(): string
    {
        return $this->class;
    }


    public function getParameters(): array
    {
        return $this->parameters;
    }


}