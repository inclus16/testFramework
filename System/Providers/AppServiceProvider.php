<?php


namespace System\Providers;


use Ds\Map;
use System\AppContext;
use System\Config\App\AppConfig;
use System\Config\System\RouterConfig;
use System\Exceptions\Handler;
use System\Http\Pipeline;
use System\Http\Routing\ControllerParameterResolver;
use System\Http\Routing\RouteParametersValidator;
use System\Http\Routing\RouterResolver;
use System\Http\Server;
use System\Http\Validation\ValidationProvider;
use System\InversionOfControl\ControllersDescriptor;
use System\InversionOfControl\ServiceCollection;
use System\InversionOfControl\ServiceProvider;
use System\Log\PipelineLogger;

abstract class AppServiceProvider
{
    protected abstract function registerServices(ServiceCollection $collection): void;

    public function boot(string $baseDir): ServiceProvider
    {
        $collection = new ServiceCollection();
        $appContext = new AppContext($baseDir);
        $collection->addCompletedSingleton($appContext);
        $collection->addSingleton(AppConfig::class);
        $collection->addSingleton(RouterConfig::class);
        $collection->addSingleton(RouterResolver::class);
        $collection->addSingleton(Server::class);
        $collection->addSingleton(PipelineLogger::class);
        $collection->addScoped(RouterResolver::class);
        $collection->addScoped(ValidationProvider::class);
        $collection->addScoped(ControllerParameterResolver::class);
        $collection->addScoped(RouteParametersValidator::class);
        $collection->addScoped(Handler::class);
        $collection->addScoped(Pipeline::class);
        $this->registerServices($collection);
        $routerConfig = new RouterConfig($appContext);
        $middlewares = $routerConfig->getMiddlewares();
        foreach ($middlewares as $middleware) {
            $collection->addScoped($middleware);
        }
        $controllers = $routerConfig->getControllers();
        $controllersDescriptor = new ControllersDescriptor();
        foreach ($controllers as $controller) {
            $collection->addScoped($controller);
            $reflectionController = new \ReflectionClass($controller);
            /** @var \ReflectionMethod $reflectionMethod */
            foreach ($reflectionController->getMethods() as $reflectionMethod) {
                if ($reflectionMethod->isPublic() && !$reflectionMethod->isConstructor()) {
                    $parameters = new Map();
                    foreach ($reflectionMethod->getParameters() as $parameter) {
                        $parameters->put($parameter->getName(), $parameter->getType()->getName());
                    }
                    $controllersDescriptor->set($controller, $reflectionMethod->getName(), $parameters);
                }
            }
        }
        $collection->addCompletedSingleton($controllersDescriptor);
        return $collection->buildServiceProvider();
    }
}