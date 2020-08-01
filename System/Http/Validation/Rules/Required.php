<?php


namespace System\Http\Validation\Rules;


use System\Http\Dto\ValidationResult;

class Required extends AbstractRule
{
    private string $message = '{0} is required';

    function validate(string $name, $field): ValidationResult
    {
        $this->message = str_replace('{0}', $name, $this->message);
        if (is_null($field)) {
            return ValidationResult::createWithError($this->getMessage());
        }
        return ValidationResult::createSuccess();
    }

    function setErrorMessage(string $message): IRule
    {
        $this->message = $message;
    }

    protected function getMessage(): string
    {
        return $this->message;
    }
}