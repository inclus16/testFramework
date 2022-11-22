<?php


namespace System\Exceptions\InversionOfControl;


use System\Exceptions\Handler;
use System\Exceptions\AbstractSystemException;

class ServiceNotFoundException extends AbstractSystemException
{


    public function __construct(private readonly string $parameter)
    {
        $this->message = "Cannot resolve parameter $this->parameter for DI";
    }

    public function getType(): int
    {
        return Handler::SYSTEM;
    }
}