<?php

namespace System\Log\Abstraction;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Swoole\Coroutine;
use Swoole\Coroutine\WaitGroup;
use System\AppContext;
use System\Config\App\AppConfig;

abstract class Logger extends AbstractLogger
{
    private const DIR_NAME = 'logs';

    private readonly string $dirFullPath;

    private bool $isDebug;

    private \SplFileObject $fileObject;

    public function __construct(private readonly AppContext $context,
                                AppConfig                   $config, protected string $name)
    {
        $this->dirFullPath = $this->context->getBaseDirectory() . '/' . self::DIR_NAME . '/' . $this->name;
        $this->initDir();
        $this->openFile();
        $this->isDebug = $config->get('debug');

    }

    private function initDir(): void
    {
        if (!file_exists($this->dirFullPath)) {
            mkdir($this->dirFullPath, 777, true);
        }
    }

    private function getTodayFilePath(): string
    {
        $today = (new \DateTime())->format('Y-m-d');
        return $this->dirFullPath . '/' . $this->name . '_' . $today;
    }

    private function openFile(): void
    {
        $this->fileObject = new \SplFileObject($this->getTodayFilePath(), 'a');
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        if ($level === LogLevel::DEBUG && !$this->isDebug)
            return;
        $this->fileObject->fwrite($this->format($level, $message, $context));
    }

    private function format($level, \Stringable|string $message, array $context = []): string
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s:v');
        $mainMessage = "[$level][$now]: $message";
        if (!empty($context['fd'])) {
            $mainMessage .= "FD: " . $context['fd'];
        }
        if (!empty($context['exception']) && $context['exception'] instanceof \Exception) {
            $exception = $context['exception'];
            $mainMessage .= "\n Exception: " . $exception->getMessage() . ' File: ' . $exception->getFile() . ' Line: ' . $exception->getLine() . "\n";
            $traceArray = $exception->getTrace();
            for ($i = 0; $i < count($traceArray); $i++) {
                if (!empty($traceArray[$i]['class']))
                    $mainMessage .= "#$i {$traceArray[$i]['class']}({$traceArray[$i]['line']})\n";
            }
        }
        return "$mainMessage\n";
    }
}