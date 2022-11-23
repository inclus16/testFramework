<?php


namespace System\Exceptions;


use Psr\Log\LogLevel;
use System\Config\App\AppConfig;
use System\Http\Pages\ExceptionPage;
use System\Http\Responses\Response;
use System\Log\PipelineLogger;

class Handler
{

    private readonly bool $isDebug;

    public function __construct(protected AppConfig             $config,
                                private readonly PipelineLogger $logger)
    {
        $this->isDebug = $config->get('debug');
    }

    public function handle(\Throwable $exception): Response
    {
        $response = new Response();
        if ($this->isDebug) {
            $response->body = (new ExceptionPage($exception))->render();
        }
        $this->logger->error($exception->getMessage());
        $response->status = 500;
        return $response;
    }

}