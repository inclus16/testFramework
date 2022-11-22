<?php


namespace System\InversionOfControl;

use Ds\Map;
use System\Exceptions\InversionOfControl\ServiceNotFoundException;
use Ds\Vector;

class ServiceCollection
{
    private Vector $singletons;

    private Vector $scoped;

    private Vector $compiledSingletons;

    public function __construct()
    {
        $this->singletons = new Vector();
        $this->scoped = new Vector();
        $this->compiledSingletons = new Vector();
    }

    public function buildServiceProvider(): ServiceProvider
    {
        return $this->compile();
    }

    public function addScoped(string $class): void
    {
        if (!$this->scoped->contains($class)) {
            $this->scoped->push($class);
        }
    }

    public function addSingleton(string $class): void
    {
        if (!$this->singletons->contains($class)) {
            $this->singletons->push($class);
        }
    }

    public function addCompletedSingleton(object $singleton): void
    {
        $this->compiledSingletons->push($singleton);
    }

    public function get(string $class): ?object
    {
        return $this->singletons->filter(fn(object $obj) => $obj instanceof $class)->first();
    }

    private function compile(): ServiceProvider
    {
        foreach ($this->singletons as $class) {
            $reflection = new \ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
                $this->compiledSingletons->push($reflection->newInstance());
            } else {
                $this->compiledSingletons->push($reflection->newInstanceArgs($this->getParameters($constructor->getParameters(), $this->compiledSingletons)));
            }
        }
        $compiledScoped = new Vector();
        foreach ($this->scoped as $class) {
            $reflection = new \ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
                $compiledScoped->push(new TemporaryServiceDescriptor($class, new Vector()));
            } else {
                $parameters = $constructor->getParameters();
                $compiledScoped->push(new TemporaryServiceDescriptor($class, new Vector(array_map(function (\ReflectionParameter $parameter) {
                    return $parameter->getType()->getName();
                }, $parameters))));
            }
        }
        $sp = ServiceProvider::getInstance();
        $sp->setCompiledServices($this->compiledSingletons, $compiledScoped);
        return $sp;
    }

    /**
     * @param \ReflectionParameter[] $classes
     * @param Vector $compiledClasses
     * @return array
     */
    private function getParameters(array $classes, Vector $compiledClasses): array
    {
        $parameters = [];
        if (empty($classes)) {
            return $classes;
        }
        /** @var \ReflectionParameter $class */
        foreach ($classes as $class) {
            $objectParameterSequence = $compiledClasses->filter(function (object $compiledClass) use ($class) {
                return get_class($compiledClass) === $class->getType()?->getName();
            });
            if ($objectParameterSequence->isEmpty()) {
                throw new ServiceNotFoundException($class->name);
            }
            $parameters[] = $objectParameterSequence->first();
        }
        return $parameters;
    }
}