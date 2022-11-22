<?php


namespace System\Config\System;


use Ds\Map;
use System\Http\Dto\RouteConfigItem;
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
            $dto = new RouteConfigItem($route['method'], $route['controller'], $route['action'], $route['name'], $route['path'], new Vector($route['middlewares']));
            $this->routes->push($dto);
        }
    }

    public function getControllers(): Sequence
    {
        $uniqueControllers = new Vector();
        foreach ($this->invokeGetter('getController') as $controllers) {
            $uniqueControllers->push($controllers);
        }
        return $uniqueControllers;
    }

    private function invokeGetter(string $getter): Sequence
    {
        return $this->routes->map(fn(RouteConfigItem $item) => $item->$getter());
    }

    public function getRoutes(): Vector
    {
        return $this->routes;
    }

    public function getPaths(): Sequence
    {
        return $this->invokeGetter('getPath');
    }

    public function getMiddlewares(): Sequence
    {
        $uniqueMiddlewares = new Vector();
        foreach ($this->invokeGetter('getMiddlewares') as $middlewaresPerRoute) {
            foreach ($middlewaresPerRoute as $middleware) {
                if (!$uniqueMiddlewares->contains($middleware)) {
                    $uniqueMiddlewares->push($middleware);
                }
            }
        }
        return $uniqueMiddlewares;
    }
}