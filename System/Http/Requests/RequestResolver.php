<?php

namespace System\Http\Requests;

use Swoole\Http\Request;

class RequestResolver
{
    public static function resolve(string $controller, string $method, Request $request): ?BasicRequest
    {
    }
}