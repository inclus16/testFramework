<?php


namespace System\Http\Dto;


class Cookie
{


    public function __construct(private readonly string $name,
                                private readonly string $value = '',
                                private readonly int    $expires = 0,
                                private readonly string $path = '',
                                private readonly string $domain = '',
                                private readonly bool   $secure = false,
                                private readonly bool   $httpOnly = false,
                                private readonly string $samesite,
                                private readonly string $priority)
    {
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getValue(): string
    {
        return $this->value;
    }


    public function getExpires(): int
    {
        return $this->expires;
    }


    public function getPath(): string
    {
        return $this->path;
    }


    public function getDomain(): string
    {
        return $this->domain;
    }


    public function isSecure(): bool
    {
        return $this->secure;
    }


    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }


    public function getSamesite(): string
    {
        return $this->samesite;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }
}