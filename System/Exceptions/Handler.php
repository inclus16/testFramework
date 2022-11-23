<?php


namespace System\Exceptions;


use System\Config\App\AppConfig;
use System\Http\Responses\Response;

class Handler
{
    protected AppConfig $config;

    private readonly bool $isDebug;

    public function __construct(AppConfig $config)
    {
        $this->config = $config;
        $this->isDebug = $config->get('debug');
    }

    public function handle(\Throwable $exception): Response
    {
        $response = new Response();
        if ($this->isDebug) {
            $message = $exception->getMessage();
            $trace = $exception->getTraceAsString();
            $response->body = "$message \n $trace";
        }
        $response->status = 500;
        return $response;
    }

}