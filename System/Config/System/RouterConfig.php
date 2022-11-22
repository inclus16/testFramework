<?php


namespace System\Config\System;


use Ds\Map;
use System\Http\Dto\RouteConfigItem;
use Ds\Sequence;
use Ds\Vector;

class RouterConfig
{
    private const CONFIG_NAME = 'routing.json';

    private Map $routes;

    public function __construct()
    {
        $this->routes = new Map();
        $this->parseConfig();
    }

    private function parseConfig()
    {
        $routes = json_decode(file_get_contents(realpath(__DIR__ . '/../../../config/' . self::CONFIG_NAME)), true);
        foreach ($routes as $route) {
            $dto = new RouteConfigItem($route['method'], $route['controller'], $route['action'], $route['name'], $route['path'], new Vector($route['middlewares']));
            if (!$this->routes->hasKey($route['path'])) {
                $this->routes[$route['path']] = new Map();
            }
            $this->routes[$route['path']]->put($route['method'], $dto);
        }
    }

    public function getControllers(): Sequence
    {
        return $this->invokeGetter('getController');
    }

    private function invokeGetter(string $getter): Sequence
    {
        $itemsCollapsed = new Vector();
        $itemsNotCollapsed = $this->routes->map(function (string $key, Map $methods) use ($getter) {
            return $methods->map(function (string $ketMethod, RouteConfigItem $routeItem) use ($getter) {
                return $routeItem->$getter();
            })->values();
        })->values();
        foreach ($itemsNotCollapsed as $itemVector) {
            foreach ($itemVector as $item) {
                if (!$itemsCollapsed->contains($item)) {
                    $itemsCollapsed->push($item);
                }
            }
        }
        return $itemsCollapsed;
    }

    public function getConfig(): Map
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