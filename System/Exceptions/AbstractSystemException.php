<?php


namespace System\Exceptions;


abstract class AbstractSystemException extends \Exception
{
    public abstract function getType():int;

}