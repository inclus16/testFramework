<?php

namespace System\Http\Routing;

use Ds\Map;
use Ds\Vector;
use Swoole\Http\Request;
use System\Http\Requests\BasicRequest;

class ControllerParameterResolver
{
    public function __construct()
    {
    }

    public function resolve(Map $expectedParameters, Map $parameters, Request $request): Vector
    {
        $resolvedParameters = new Vector();
        foreach ($expectedParameters as $parameterName => $parameterType) {
            if ($parameterName === 'request') {
                $resolvedParameters->push(new $parameterType(BasicRequest::createFromSwooleRequest($request)));
            } else {
                $resolvedParameters->push($parameters[$parameterName]);
            }
        }
        return $resolvedParameters;
    }
}