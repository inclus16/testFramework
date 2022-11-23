<?php

namespace Unit\InversionOfControl\Fake;

class DependentFakeService2
{
    public function __construct(private readonly DependentFakeService1 $service)
    {
    }

    public function getResult(): int
    {
        return $this->service->getResult();
    }
}