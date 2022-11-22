<?php


namespace System;


class AppContext
{

    public function __construct(private readonly string $baseDir)
    {
    }


    public function getBaseDirectory(): string
    {
        return $this->baseDir;
    }
}