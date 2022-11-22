<?php


namespace System\Providers;


use System\AppContext;
use System\Config\App\AppConfig;
use System\Config\System\RouterConfig;
use System\Http\Pipeline;
use System\Http\Routing\RouterService;
use System\Http\Server;
use System\Http\Validation\ValidationProvider;
use System\InversionOfControl\ServiceCollection;
use System\InversionOfControl\ServiceProvider;

abstract class AppServiceProvider
{
    protected abstract function registerServices(): void;

    public function boot(): ServiceProvider
    {
        $collection = new ServiceCollection();

        $collection->addSingleton(AppContext::class);
        $collection->addSingleton(AppConfig::class);
        $collection->addSingleton(RouterConfig::class);
        $collection->addSingleton(RouterService::class);
        $collection->addSingleton(Server::class);
        $collection->addScoped(RouterService::class);
        $collection->addScoped(ValidationProvider::class);
        $collection->addScoped(Pipeline::class);
        $this->registerServices();
        $routerConfig = new RouterConfig();
        $middlewares = $routerConfig->getMiddlewares();
        foreach ($middlewares as $middleware) {
            $collection->addScoped($middleware);
        }
        $controllers = $routerConfig->getControllers();
        foreach ($controllers as $controller) {
            $collection->addScoped($controller);
        }
        return $collection->buildServiceProvider();
    }
}