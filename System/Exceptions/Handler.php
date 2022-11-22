<?php


namespace System\Exceptions;


use System\Config\App\AppConfig;
use System\Exceptions\Http\BadRequestException;
use System\Exceptions\Http\RouteNotFoundException;
use System\Exceptions\Http\UnsupportedMediaTypeException;
use System\Http\Responses\JsonResponse;
use System\Http\Responses\Response;

class Handler
{
    public const SYSTEM = 0;

    public const HTTP = 1;

    protected AppConfig $config;

    public function __construct(AppConfig $config)
    {
        $this->config = $config;
    }

    public function handle(\Throwable $exception): Response
    {

    }

    private function handleSystemException(AbstractSystemException $exception): Response
    {
        $response = new Response();
        $response->status = 500;
        return $response;
    }

}