<?php

namespace System\Http\Pages\Abstractions;

interface PageInterface
{
    public function render(): string;
}