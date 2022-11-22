<?php


namespace System\Http\Routing;


use System\Config\System\RouterConfig;
use System\Http\Dto\RouteConfigItem;

class RouterService
{


    public function __construct(private readonly RouterConfig $config)
    {
    }

    public function getConfig(string $path, string $method): ?RouteConfigItem
    {
        $routes = $this->config->getConfig();
        if (!empty($routes[$path][$method])) {
            return $routes[$path][$method];
        }
        return null;
    }
}