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

    public function handle(\Exception $exception): Response
    {
        if ($exception instanceof AbstractSystemException) {
            return $this->handleSystemException($exception);
        }
        if ($this->config->get('debug')) {
            return JsonResponse::create()
                ->setStatusCode(500)
                ->setArray([
                    'status' => 500,
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace()
                ]);
        } else {
            return Response::create()->setStatusCode(500);
        }
    }

    private function handleSystemException(AbstractSystemException $exception): Response
    {
        $type = $exception->getType();
        switch ($type) {
            case self::HTTP:
                return $this->handleHttp($exception);
            default:
                return Response::create()
                    ->setStatusCode(500)
                    ->setBody($exception->getMessage());
        }
    }

    private function handleHttp(AbstractSystemException $exception)
    {
        $class = get_class($exception);
        switch ($class) {
            case BadRequestException::class:
                return JsonResponse::create()->setStatusCode(400)
                    ->setArray(['errors' => $exception->getMessages()]);
            case UnsupportedMediaTypeException::class:
                return Response::create()
                    ->setStatusCode(415);
            case RouteNotFoundException::class:
                return Response::create()
                    ->setStatusCode(404);
            default:
                return Response::create()
                    ->setStatusCode(500)
                    ->setBody($exception->getMessage());
        }
    }

}