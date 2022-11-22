<?php


namespace System\Http\Validation\Rules;


use System\Http\Dto\ValidationResult;

class Required implements RuleInterface
{
    private string $message = '{0} is required';

    public function validate(string $name, $field): bool
    {
        $this->message = str_replace('{0}', $name, $this->message);
        return is_null($field);
    }

    public function setErrorMessage(string $message): RuleInterface
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}