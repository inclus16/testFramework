<?php

namespace System\Http\Dto;

use Ds\Map;

class ResolvedRouteDto
{

    public function __construct(private readonly RouteConfigItem $routeConfigItem,
                                private readonly Map          $parameters)
    {
    }

    public function getRouteConfigItem(): RouteConfigItem
    {
        return $this->routeConfigItem;
    }

    public function getParameters(): Map
    {
        return $this->parameters;
    }
}