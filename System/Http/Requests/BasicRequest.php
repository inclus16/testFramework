<?php


namespace System\Http\Requests;


use Ds\Map;
use Swoole\Http\Request;

class BasicRequest
{
    private string $method;

    private string $path;

    private Map $headers;

    private Map $fields;

    private function __construct(string $method, string $path, Map $headers, Map $fields)
    {
    }

    public static function createFromSwooleRequest(Request $request): self
    {
        return new self($request->getMethod(), $request->server['path_info'], new Map($request->header), new Map($request->post));
    }

    private function setFields(): void
    {
        $this->fields = new Map();
        $this->fields = match ($this->method) {
            'GET', 'DELETE', 'HEAD' => new Map($_GET),
            'POST', 'PATCH', 'PUT' => new Map($_POST),
            default => throw new \Exception('ewqeqwe'),
        };
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function hasHeader(string $key)
    {
        return $this->headers->hasKey($key);
    }

    public function getHeader(string $key)
    {
        return $this->headers[$key];
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getFieldValue(string $key)
    {
        if ($this->fields->hasKey($key)) {
            return $this->fields[$key];
        }
        return null;
    }
}