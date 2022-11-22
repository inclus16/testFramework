<?php

namespace System\Http\Routing;

use Ds\Map;
use Ds\Vector;

class RouteParametersValidator
{
    private readonly Vector $validatableParameterTypes;

    public function __construct()
    {
        $this->validatableParameterTypes = new Vector(['int', 'string', 'float', 'double']);
    }

    public function validate(Map $expectedParameters, Map $providedParameters): bool
    {
        foreach ($expectedParameters as $expectedParameterName => $expectedParameterType) {
            if (($this->validatableParameterTypes->contains($expectedParameterType)
                && (!$providedParameters->hasKey($expectedParameterName)
                    || !$this->validateType($expectedParameterType, $providedParameters[$expectedParameterName])))) {
                return false;
            }
        }
        return true;
    }

    private function validateType(string $expectedType, $value): bool
    {
        return match ($expectedType) {
            'string' => true,
            'int', 'float', 'double' => is_numeric($value),
            default => throw new \InvalidArgumentException('Route parameter type validation is not supported for type: ' . $expectedType),
        };
    }
}