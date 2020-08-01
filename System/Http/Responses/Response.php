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
    private Vector $headers;

    /**
     * @var Cookie[]
     */
    private Vector $cookies;

    private int $status = 200;


    private $body;


    public function __construct()
    {
        $this->headers = new Vector();
        $this->cookies = new Vector();
    }

    public static function create(): self
    {
        $caller = get_called_class();
        return (new $caller());
    }

    public function setHeader(string $key, string $value): self
    {
        $this->headers->push(new Header($key, $value));
        return $this;
    }

    public function setCookies(string $name,
                               string $value = '',
                               int $expires = 0,
                               string $path = '',
                               string $domain = '',
                               bool $secure = false,
                               bool $httpOnly = false): self
    {
        $this->cookies->push(new Cookie($name, $value, $expires, $path, $domain, $secure, $httpOnly));
        return $this;
    }

    public function setStatusCode(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    public function sendResponse()
    {
        foreach ($this->cookies as $cookie) {
            setcookie($cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpires(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttpOnly());
        }
        foreach ($this->headers as $header) {
            \header($header->getKey() . ': ' . $header->getValue());
        }
        http_response_code($this->status);
        echo $this->body;
    }

}