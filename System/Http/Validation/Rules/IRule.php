<?php


namespace System\Http\Validation\Rules;


use System\Http\Dto\ValidationResult;

interface IRule
{
    /**
     * @param string $name
     * @param string|null $value
     * @return ValidationResult
     */
    function validate(string $name, $value):ValidationResult;

    function setErrorMessage(string $message):self;

}