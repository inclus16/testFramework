<?php


namespace System\Config\System;


use System\Http\Dto\RequestConfigItem;
use Ds\Sequence;
use Ds\Vector;

class RouterConfig
{
    private const CONFIG_NAME = 'routing.json';

    private Vector $routes;

    public function __construct()
    {
        $this->routes = new Vector();
        $this->parseConfig();
    }

    private function parseConfig()
    {
        $routes = json_decode(file_get_contents(realpath(__DIR__ . '/../../../config/' . self::CONFIG_NAME)), true);
        foreach ($routes as $route) {
            $dto = new RequestConfigItem($route['method'], $route['controller'], $route['action'], $route['name'], $route['path']);
            $this->routes->push($dto);
        }
    }

    public function getControllers(): Sequence
    {
        return $this->routes->map(function (RequestConfigItem $item) {
            return $item->getController();
        });
    }

    public function getRoutes(): Vector
    {
        return $this->routes;
    }
}