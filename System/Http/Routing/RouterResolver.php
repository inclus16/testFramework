<?php


namespace System\Http\Routing;


use Ds\Map;
use System\Config\System\RouterConfig;
use System\Http\Dto\ResolvedRouteDto;
use System\Http\Dto\RouteConfigItem;

class RouterResolver
{


    public function __construct(private readonly RouterConfig $config)
    {
    }

    public function resolveRoute(string $path, string $method): ?ResolvedRouteDto
    {
        $pathSegments = explode('/', $path);
        array_shift($pathSegments);
        $routes = $this->config->getRoutes()->filter(fn(RouteConfigItem $item) => $item->getMethod() === $method);
        $resolvedRoute = null;
        /** @var RouteConfigItem $route */
        foreach ($routes as $route) {
            $routePaths = explode('/', $route->getPath());
            $routeParameters = new Map();
            array_shift($routePaths);
            if (count($routePaths) === count($pathSegments)) {
                for ($i = 0; $i < count($routePaths); $i++) {
                    if ($routePaths[$i] === $pathSegments[$i]) {
                        continue;
                    }
                    if (str_starts_with($routePaths[$i], '{')) {
                        $routeParameters->put(str_replace(['{', '}'], '', $routePaths[$i]), $pathSegments[$i]);
                    } else {
                        $resolvedRoute = null;
                        break;
                    }
                    $resolvedRoute = $route;
                }
                if ($resolvedRoute !== null) {
                    return new ResolvedRouteDto($resolvedRoute, $routeParameters);
                }
            }
        }
        return null;
    }
}