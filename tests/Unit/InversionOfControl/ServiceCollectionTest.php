<?php

namespace Unit\InversionOfControl;

use PHPUnit\Framework\TestCase;
use System\InversionOfControl\ServiceCollection;
use Unit\InversionOfControl\Fake\DependentFakeService1;
use Unit\InversionOfControl\Fake\DependentFakeService2;
use Unit\InversionOfControl\Fake\FakeService;

class ServiceCollectionTest extends TestCase
{
    public function testCompileSuccessSingleton(): void
    {
        $serviceCollection = new ServiceCollection();
        $serviceCollection->addSingleton(FakeService::class);
        $serviceCollection->addSingleton(DependentFakeService1::class);
        $serviceCollection->addScoped(DependentFakeService2::class);
        $sp = $serviceCollection->buildServiceProvider();
        $this->assertEquals(FakeService::RESULT, $sp->getService(DependentFakeService1::class)->getResult());
    }

    public function testCompileSuccessScoped(): void
    {
        $serviceCollection = new ServiceCollection();
        $serviceCollection->addSingleton(FakeService::class);
        $serviceCollection->addSingleton(DependentFakeService1::class);
        $serviceCollection->addScoped(DependentFakeService2::class);
        $sp = $serviceCollection->buildServiceProvider();
        $scoped = $sp->createScopedServices();
        $this->assertEquals(FakeService::RESULT, $scoped->getService(DependentFakeService2::class)->getResult());
    }
}