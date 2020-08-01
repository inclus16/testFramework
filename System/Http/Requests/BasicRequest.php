<?php


namespace System\Http\Requests;


use Ds\Map;

class BasicRequest
{
    private string $method;

    private string $path;

    private Map $headers;

    private Map $fields;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = strtok($_SERVER['REQUEST_URI'],'?');
        $this->headers = new Map(getallheaders());
        $this->setFields();
    }

    private function setFields(): void
    {
        $this->fields = new Map();
        switch ($this->method) {
            case 'GET':
            case 'DELETE':
            case 'HEAD':
                $this->fields = new Map($_GET);
                break;
            case 'POST':
            case 'PATCH':
            case 'PUT':
                $this->fields = new Map($_POST);
                break;
            default:
                throw new \Exception('ewqeqwe');
        }
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