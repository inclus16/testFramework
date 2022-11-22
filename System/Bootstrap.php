<?php


namespace System;


use App\Providers\AppServiceProvider;
use System\Http\Server;
use System\InversionOfControl\ServiceProvider;
use function Swoole\Coroutine\run;

class Bootstrap
{
    private readonly ServiceProvider $appServiceProvider;

    public function boot()
    {
        run(function () {
            $services = new AppServiceProvider();
            $this->appServiceProvider = $services->boot();
            $this->startServer();
        });
    }

    public function startServer(): void
    {
        /** @var Server $server */
        $server = $this->appServiceProvider->getService(Server::class);
        $server->start();
    }
}