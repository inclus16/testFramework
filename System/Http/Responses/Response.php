<?php


namespace System\Http\Responses;


use Ds\Vector;
use System\Http\Dto\Cookie;
use System\Http\Dto\Header;

class Response
{

    /**
     * @var Header[]
     */
    public Vector $headers;

    /**
     * @var Cookie[]
     */
    public Vector $cookies;

    public int $status = 200;


    public string $body;


    public function __construct()
    {
        $this->headers = new Vector();
        $this->cookies = new Vector();
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers->push(new Header($key, $value));
        return $this;
    }

    public function setCookies(string $name,
                               string $value = '',
                               int    $expires = 0,
                               string $path = '',
                               string $domain = '',
                               bool   $secure = false,
                               bool   $httpOnly = false,
                               string $samesite = '',
                               string $priority = ''): self
    {
        $this->cookies->push(new Cookie($name, $value, $expires, $path, $domain, $secure, $httpOnly, $samesite, $priority));
        return $this;
    }

}