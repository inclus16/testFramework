<?php


namespace System\Exceptions\InversionOfControl;


use App\Src\Exceptions\Handler;
use System\Exceptions\AbstractSystemException;

class ServiceNotFoundException extends AbstractSystemException
{

    public function getType(): int
    {
        return Handler::SYSTEM;
    }
}