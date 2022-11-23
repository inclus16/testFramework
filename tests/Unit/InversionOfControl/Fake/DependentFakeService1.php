<?php

namespace Unit\InversionOfControl\Fake;

class DependentFakeService1
{

    public function __construct(private readonly FakeService $service)
    {
    }

    public function getResult(): int
    {
        return $this->service->getResult();
    }
}