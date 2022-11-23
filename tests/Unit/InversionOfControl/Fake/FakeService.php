<?php

namespace Unit\InversionOfControl\Fake;

class FakeService
{
    public const RESULT = 5;

    public function getResult(): int
    {
        return self::RESULT;
    }
}