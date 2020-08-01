<?php


namespace System\Http\Dto;


class RequestConfigItem
{
    private string $method;

    private string $controller;

    private string $controllerAction;

    private string $name;

    private string $path;

    public function __construct(string $method,
                                string $controller,
                                string $controllerAction,
                                string $name,
                                string $path)
    {
        $this->name = $name;
        $this->method = $method;
        $this->controller = $controller;
        $this->controllerAction = $controllerAction;
        $this->path = $path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getController(): string
    {
        return $this->controller;
    }


    public function getControllerAction(): string
    {
        return $this->controllerAction;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getPath(): string
    {
        return $this->path;
    }


}