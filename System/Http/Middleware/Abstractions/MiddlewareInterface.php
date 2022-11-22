<?php

namespace System\Http\Middleware\Abstractions;

use Swoole\Http\Request;
use Swoole\Http\Response;

interface MiddlewareInterface
{
    function invoke(Request $request, Response $response): bool;
}