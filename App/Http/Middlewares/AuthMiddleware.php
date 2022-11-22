<?php

namespace App\Http\Middlewares;

use Swoole\Http\Request;
use System\Http\Middleware\Abstractions\MiddlewareInterface;
use System\Http\Responses\JsonResponse;
use System\Http\Responses\Response;

class AuthMiddleware implements MiddlewareInterface
{

    function invoke(Request $request): bool
    {
        return true;
    }

    function getResponse(): ?Response
    {
        return new JsonResponse(['status' => 'Not Authorized'], 403);
    }
}