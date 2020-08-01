<?php


namespace System\Exceptions\Http;


use App\Src\Exceptions\Handler;
use System\Exceptions\AbstractSystemException;

class MethodNotAllowedException extends AbstractSystemException
{
    public function getType(): int
    {
        return Handler::HTTP;
    }
}