<?php


namespace System\InversionOfControl;


use Ds\Vector;
use System\Exceptions\InversionOfControl\ServiceNotFoundException;

class ServiceProvider
{
    private Vector $services;

    public function __construct(Vector $services)
    {
        $this->services = $services;
    }

    public function getService(string $class): object
    {
        foreach ($this->services as $service) {
            if (get_class($service) === $class) {
                return $service;
            }
        }
        throw new ServiceNotFoundException();
    }
}