<?php


namespace System\Http\Dto;


class RequestController
{
    private string $class;

    private string $action;

    public function __construct(string $class, string $action)
    {
        $this->class = $class;
        $this->action = $action;
    }


    public function getClass(): string
    {
        return $this->class;
    }


    public function getAction(): string
    {
        return $this->action;
    }


}