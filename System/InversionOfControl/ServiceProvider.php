<?php


namespace System\InversionOfControl;


use Ds\Vector;
use System\Exceptions\InversionOfControl\ServiceNotFoundException;
use System\Helpers\Arr;

class ServiceProvider
{
    private static $instance = null;

    private Vector $singletons;

    private Vector $scopedDescriptors;

    public function setCompiledServices(Vector $singletons, Vector $scoped): void
    {
        $this->singletons = $singletons;
        $this->scopedDescriptors = $scoped;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getService(string $class): object
    {
        foreach ($this->singletons as $service) {
            if (get_class($service) === $class) {
                return $service;
            }
        }
        throw new ServiceNotFoundException('Service .' . $class . ' is not present in collection');
    }

    public function createScopedServices(): ScopedServices
    {
        $services = new Vector();
        /** @var TemporaryServiceDescriptor $descriptor */
        foreach ($this->scopedDescriptors as $descriptor) {
            $class = $descriptor->getClass();
            if ($descriptor->getConstructorParameters()->isEmpty()) {
                $services->push(new $class());
            }
            $resolvedParameters = new Vector();
            foreach ($descriptor->getConstructorParameters() as $constructorParameterType) {
                $resolvedParameter = Arr::first($this->singletons, fn(object $obj) => get_class($obj) === $constructorParameterType);
                if ($resolvedParameter !== null) {
                    $resolvedParameters->push($resolvedParameter);
                } else {
                    $scopedServiceAsParameter = Arr::first($services, fn(object $obj) => get_class($obj) === $constructorParameterType);
                    if ($scopedServiceAsParameter === null) {
                        throw new ServiceNotFoundException($constructorParameterType);
                    }
                    $resolvedParameters->push($scopedServiceAsParameter);
                }
            }
            $services->push(new $class(...$resolvedParameters));
        }
        return new ScopedServices($services);
    }
}