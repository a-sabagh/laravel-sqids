<?php

namespace LaravelSqids\Tests;

use LaravelSqids\SqidsAdapter;
use LaravelSqids\SqidsManager;
use LaravelSqids\SqidsServiceProvider;
use Orchestra\Testbench\TestCase;
use ReflectionClass;

class SqidsManagerTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SqidsServiceProvider::class,
        ];
    }

    public function test_manager_bind_as_singleton_object(): void
    {
        $sqids = $this->app->get(SqidsManager::class);

        $this->assertInstanceOf(SqidsManager::class, $sqids);

        $anotherSqids = $this->app->get(SqidsManager::class);

        $this->assertSame($sqids, $anotherSqids);
    }

    public function test_manager_can_instanciate_default_sqid(): void
    {
        $this->app->config->set('sqids.default', 'default');

        $this->app->config->set('sqids.drivers.default', [
            'pad' => '5000',
            'length' => 6,
        ]);

        $defaultDriver = $this->app->get(SqidsManager::class)->driver();

        $this->assertInstanceOf(SqidsAdapter::class, $defaultDriver);

        $reflectionDefaultDriver = new ReflectionClass($defaultDriver);
        $lengthReflectionProperty = $reflectionDefaultDriver->getProperty('length');
        $padReflectionProperty = $reflectionDefaultDriver->getProperty('pad');
        $this->assertEquals(6, $lengthReflectionProperty->getValue($defaultDriver));
        $this->assertEquals('5000', $padReflectionProperty->getValue($defaultDriver));
    }
}
