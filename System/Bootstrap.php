<?php


namespace System;


use System\Http\Server;
use System\InversionOfControl\ServiceProvider;
use System\Providers\AppServiceProvider;
use function Swoole\Coroutine\run;

class Bootstrap
{
    private readonly ServiceProvider $serviceProvider;


    public function boot(AppServiceProvider $appServiceProvider)
    {
        run(function () use ($appServiceProvider) {
            $this->serviceProvider = $appServiceProvider->boot();
            $this->startServer();
        });
    }

    public function startServer(): void
    {
        /** @var Server $server */
        $server = $this->serviceProvider->getService(Server::class);
        $server->start();
    }
}