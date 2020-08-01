<?php


namespace App\Providers;



use System\InversionOfControl\ServiceCollection;
use System\InversionOfControl\ServiceProvider;

class AppServiceProvider
{
    public function boot(): ServiceProvider
    {
        $collection = new ServiceCollection();
        // register your services
        return $collection->buildServiceProvider();
    }
}