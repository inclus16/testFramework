<?php


namespace System;


use System\Http\Server;
use System\InversionOfControl\ServiceProvider;
use System\Providers\AppServiceProvider;
use function Swoole\Coroutine\run;

class Bootstrap
{
    private readonly ServiceProvider $serviceProvider;

    public function __construct(private readonly string $baseDir)
    {
    }


    public function boot(AppServiceProvider $provider)
    {
        run(function () use ($provider) {
            $this->serviceProvider = $provider->boot($this->baseDir);
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