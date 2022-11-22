<?php

namespace System\Http;

use Swoole\Http\Request;
use Swoole\Http\Response;
use System\Config\System\RouterConfig;
use System\InversionOfControl\ServiceProvider;

class Server
{

    private readonly \Swoole\Coroutine\Http\Server $server;

    public function __construct(private readonly RouterConfig $routerConfig)
    {
    }

    /**
     * Обработка соединения производится в отдельной подпрограмме, а клиентское соединение Connect, Request, Response, Close полностью последовательное
     * @see https://wiki.swoole.com/#/coroutine/http_server
     * @return void
     */
    private function initServer(): void
    {
        $this->server = new \Swoole\Coroutine\Http\Server('php', 9501, false);
        foreach ($this->routerConfig->getPaths() as $routePath) {
            $this->server->handle($routePath, function (Request $request, Response $response) {
                $scoped = ServiceProvider::getInstance()->createScopedServices();
                $scoped->getService(Pipeline::class)->invoke($request, $response, $scoped);
            });
        }

    }

    public function start(): void
    {
        $this->initServer();
        $this->server->start();
    }
}