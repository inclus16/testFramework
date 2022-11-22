<?php


namespace System\Http\Requests;


use Ds\Map;
use Swoole\Http\Request;

class BasicRequest
{

    private function __construct(private readonly string $method,
                                 private readonly string $path,
                                 private readonly Map    $headers,
                                 private readonly Map    $fields,
                                 private readonly Map    $files)
    {
    }

    public static function createFromSwooleRequest(Request $request): self
    {
        $method = $request->getMethod();
        $hasBody = in_array($method, ['POST', 'PUT']);
        return new self($request->getMethod(),
            $request->server['path_info'],
            new Map($request->header) ?? [],
            new Map($hasBody ? ($request->post ?? []) : ($request->get ?? [])),
            new Map($request->files ?? []));
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function hasHeader(string $key): bool
    {
        return $this->headers->hasKey($key);
    }

    public function getHeader(string $key): string
    {
        return $this->headers[$key];
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getFieldValue(string $key): ?string
    {
        if ($this->fields->hasKey($key)) {
            return $this->fields[$key];
        }
        return null;
    }

    public function getFiles(): Map
    {
        return $this->files;
    }

    public function getFile(string $key)
    {
        return $this->files[$key] ?? null;
    }
}