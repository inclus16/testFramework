<?php


namespace System\Http\Dto;


class Cookie
{

    private string $name;

    private string $value = '';

    private int $expires = 0;

    private string $path = '';

    private string $domain = '';

    private bool $secure = false;

    private bool $httpOnly = false;


    public function __construct(string $name,
                                string $value = '',
                                int $expires = 0,
                                string $path = '',
                                string $domain = '',
                                bool $secure = false,
                                bool $httpOnly = false)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expires = $expires;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
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




}