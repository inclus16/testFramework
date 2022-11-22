<?php


namespace System\Exceptions\Http;


use System\Exceptions\AbstractSystemException;
use System\Exceptions\Handler;

class MethodNotAllowedException extends AbstractSystemException
{
    public function getType(): int
    {
        return Handler::HTTP;
    }
}