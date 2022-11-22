<?php

namespace System\InversionOfControl;

use Ds\Map;

class ControllersDescriptor
{
    private readonly Map $controllersActionData;

    public function __construct()
    {
        $this->controllersActionData = new Map();
    }

    public function set(string $controllerClass, string $actionName, Map $parameters)
    {
        $this->controllersActionData->put($this->getKey($controllerClass, $actionName), $parameters);
    }

    private function getKey(string $controllerClass, string $actionName): string
    {
        return $controllerClass . '_' . $actionName;
    }

    public function getActionParameters(string $controllerClass, string $actionName)
    {
        return $this->controllersActionData->get($this->getKey($controllerClass, $actionName));
    }


}