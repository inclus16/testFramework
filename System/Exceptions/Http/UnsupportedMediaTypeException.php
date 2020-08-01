<?php


namespace System\Exceptions\Http;


use System\Exceptions\AbstractSystemException;

class UnsupportedMediaTypeException extends AbstractSystemException
{
    public function getType(): int
    {
        return \App\Src\Exceptions\Handler::HTTP;
    }
}