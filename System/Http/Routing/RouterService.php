<?php


namespace System\Http\Routing;


use System\Config\System\RouterConfig;
use System\Exceptions\Http\RouteNotFoundException;
use System\Http\Dto\RequestConfigItem;
use System\Http\Dto\RequestController;
use Ds\Map;
use System\Http\Requests\BasicRequest;

class RouterService
{

    private BasicRequest $request;

    private Map $routeMapping;

    private RouterConfig $config;

    public function __construct(BasicRequest $request, RouterConfig $config)
    {
        $this->request = $request;
        $this->routeMapping = new Map();
        $this->config = $config;
        $this->parseConfig();
    }

    private function parseConfig()
    {
        $routes = $this->config->getRoutes();
        foreach ($routes as $dto) {
            $this->routeMapping->put($this->getRouteHash($dto->getMethod(), $dto->getPath()), $dto);
        }
    }

    public function getController(): RequestController
    {
        $hash = $this->getRouteHash($this->request->getMethod(), $this->request->getPath());
        if (!$this->routeMapping->hasKey($hash)) {
            throw new RouteNotFoundException();
        }
        /**
         * @var RequestConfigItem $dto
         */
        $dto = $this->routeMapping[$hash];
        return new RequestController($dto->getController(), $dto->getControllerAction());
    }

    private function getRouteHash(string $method, string $path): string
    {
        return $method . '|' . $path;
    }
}