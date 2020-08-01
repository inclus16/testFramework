<?php


namespace System\Http\Validation\Rules;


abstract class AbstractRule implements IRule
{
    protected abstract function getMessage(): string;
}