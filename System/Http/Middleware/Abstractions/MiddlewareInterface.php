<?php

namespace System\Http\Middleware\Abstractions;

use Swoole\Http\Request;

interface MiddlewareInterface
{
    function getResponse(): ?\System\Http\Responses\Response;

    function invoke(Request $request): bool;
}