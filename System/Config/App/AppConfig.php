<?php


namespace System\Config\App;


use Ds\Map;
use System\AppContext;

class AppConfig
{
    private const CONFIG_FILE_NAME = 'app.json';

    private AppContext $appContext;

    private Map $config;

    public function __construct(AppContext $appContext)
    {
        $this->appContext = $appContext;
        $this->parseConfig();
    }

    private function parseConfig(): void
    {
        $this->config = new Map(json_decode(file_get_contents($this->appContext->getBaseDirectory() . '/config/' . self::CONFIG_FILE_NAME), true));
    }

    public function get(string $key): mixed
    {
        return $this->config[$key];
    }
}