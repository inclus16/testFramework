<?php


namespace System\Http\Requests;


use Ds\Map;

abstract class AppRequest
{
    private BasicRequest $request;

    protected bool $isJson = false;

    public function __construct(BasicRequest $request)
    {
        $this->request = $request;
    }

    public function isJson(): bool
    {
        return $this->isJson;
    }

    public function getBasicRequest(): BasicRequest
    {
        return $this->request;
    }

    public abstract function getRules():Map;
}