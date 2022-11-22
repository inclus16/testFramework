<?php


namespace App\Providers;


use System\AppContext;
use System\Config\App\AppConfig;
use System\Config\System\RouterConfig;
use System\Http\Routing\RouterService;
use System\Http\Server;
use System\InversionOfControl\ServiceCollection;
use System\InversionOfControl\ServiceProvider;

class AppServiceProvider extends \System\Providers\AppServiceProvider
{

    protected function registerServices(): void
    {
        // TODO: Implement registerServices() method.
    }
}