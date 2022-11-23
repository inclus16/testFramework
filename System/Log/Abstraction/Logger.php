<?php

namespace System\Log\Abstraction;

use Psr\Log\AbstractLogger;
use Swoole\Coroutine\WaitGroup;
use System\AppContext;

abstract class Logger extends AbstractLogger
{
    private const DIR_NAME = 'logs';

    private readonly WaitGroup $waitGroup;

    private readonly string $dirFullPath;

    private \SplFileObject $fileObject;

    public function __construct(private readonly AppContext $context, protected string $name)
    {
        $this->waitGroup = new WaitGroup();
        $this->dirFullPath = $this->context->getBaseDirectory() . '/' . self::DIR_NAME . '/' . $this->name;
        $this->initDir();
        $this->openFile();

    }

    private function initDir(): void
    {
        if (!file_exists($this->dirFullPath)) {
            mkdir($this->dirFullPath, 777, true);
        }
    }

    private function getTodayFileName(): string
    {
        $today = (new \DateTime())->format('Y-m-d');
        return $this->name . '_' . $today;
    }

    private function openFile(): void
    {
        $this->fileObject = new \SplFileObject($this->getTodayFileName(), 'a');
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        if ($this->waitGroup->count() > 0) {
            $this->waitGroup->wait();
        }
        $this->waitGroup->add();
        $this->fileObject->fwrite($this->format($level, $message));
        $this->waitGroup->done();
    }

    private function format($level, \Stringable|string $message): string
    {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        return "[$level][$now]: $message";
    }
}