<?php


namespace System\Http\Validation\Rules;


use System\Http\Dto\ValidationResult;

interface RuleInterface
{
    /**
     * @param string $name
     * @param string|null $value
     * @return ValidationResult
     */
    function validate(string $name, mixed $value): bool;

    function setErrorMessage(string $message): self;

    function getMessage(): string;

}