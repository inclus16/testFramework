<?php


namespace System\Exceptions\Http;


use System\Exceptions\AbstractSystemException;

class RouteNotFoundException extends AbstractSystemException
{
    public function getType(): int
    {
        return \App\Src\Exceptions\Handler::HTTP;
    }
}