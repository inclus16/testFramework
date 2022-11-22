<?php

namespace System\InversionOfControl;

use Ds\Vector;

class TemporaryServiceDescriptor
{
    /**
     * @param string $class
     * @param Vector $constructorParameters another services
     * @param object|null $instance
     */
    public function __construct(private readonly string $class,
                                private readonly Vector $constructorParameters)
    {
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getConstructorParameters(): Vector
    {
        return $this->constructorParameters;
    }
}