<?php


namespace System\InversionOfControl;


use App\Src\Exceptions\Handler;
use System\Config\System\RouterConfig;
use System\Http\Pipeline;
use System\Exceptions\InversionOfControl\ServiceNotFoundException;
use System\Http\Requests\BasicRequest;
use System\Http\Routing\RouterService;
use Ds\Vector;
use System\Http\Validation\ValidationProvider;

class ServiceCollection
{
    private Vector $collection;

    private RouterConfig $routerConfig;

    public function __construct()
    {
        $this->collection = new Vector();
        $this->routerConfig = new RouterConfig();
        $this->addSystemServices();
    }

    public function buildServiceProvider(): ServiceProvider
    {
        return new ServiceProvider($this->compile());
    }

    private function addSystemServices()
    {
        $this->collection->push(BasicRequest::class);
        $this->collection->push(Handler::class);
        $this->collection->push(RouterService::class);
        $this->collection->push(ValidationProvider::class);
        $this->collection->push(Pipeline::class);
    }

    private function addControllers()
    {
        $controllers = $this->routerConfig->getControllers();
        foreach ($controllers as $controller) {
            $this->collection->push($controller);
        }
    }

    public function add(string $class)
    {
        $this->collection->push($class);
    }

    private function compile(): Vector
    {
        $compiledClasses = new Vector();
        $this->addControllers();
        $compiledClasses->push($this->routerConfig);
        foreach ($this->collection as $class) {
            $reflection = new \ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            if ($constructor === null) {
                $compiledClasses->push($reflection->newInstance());
            } else {
                $compiledClasses->push($reflection->newInstanceArgs($this->getParameters($constructor->getParameters(), $compiledClasses)));
            }
        }
        return $compiledClasses;
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
        foreach ($classes as $class) {
            $objectParameterSequence = $compiledClasses->filter(function (object $compiledClass) use ($class) {
                return get_class($compiledClass) === $class->getClass()->name;
            });
            if ($objectParameterSequence->isEmpty()) {
                throw new ServiceNotFoundException();
            }
            $parameters[] = $objectParameterSequence->first();
        }
        return $parameters;
    }
}