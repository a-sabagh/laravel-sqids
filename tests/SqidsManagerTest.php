<?php

namespace LaravelSqids\Tests;

use InvalidArgumentException;
use LaravelSqids\SqidsAdapter;
use LaravelSqids\SqidsManager;
use ReflectionClass;

class SqidsManagerTest extends TestCase
{
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

    public function test_manager_instanciate_default_sqid_with_default_properties(): void
    {
        $this->app->config->set('sqids.default', 'default');

        $defaultDriver = $this->app->get(SqidsManager::class)->driver();
        $reflectionDefaultDriver = new ReflectionClass($defaultDriver);
        $lengthReflectionProperty = $reflectionDefaultDriver->getProperty('length');
        $padReflectionProperty = $reflectionDefaultDriver->getProperty('pad');

        $this->assertInstanceOf(SqidsAdapter::class, $defaultDriver);
        $this->assertNotNull($lengthReflectionProperty->getValue($defaultDriver));
        $this->assertEmpty($padReflectionProperty->getValue($defaultDriver));
    }

    public function test_default_config_merge_successfully(): void
    {
        $this->app->get(SqidsManager::class)->driver();

        $this->addToAssertionCount(1);
    }

    public function test_manager_instanciation_failed_beacuse_of_missing_driver(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Sqids Driver [pelikan-never-died] is not defined in sqids configuration.');

        $this->app->get(SqidsManager::class)->driver('pelikan-never-died');
    }

    public function test_sqids_manager_driver_on_the_fly(): void
    {
        $this->app->config->set('sqids.drivers.url_endpoint', [
            'pad' => '5000',
            'length' => 6,
        ]);

        $urlEndpointDriver = $this->app->get(SqidsManager::class)->driver('url_endpoint');

        $reflectionDefaultDriver = new ReflectionClass($urlEndpointDriver);
        $lengthReflectionProperty = $reflectionDefaultDriver->getProperty('length');
        $padReflectionProperty = $reflectionDefaultDriver->getProperty('pad');

        $this->assertEquals(6, $lengthReflectionProperty->getValue($urlEndpointDriver));
        $this->assertEquals('5000', $padReflectionProperty->getValue($urlEndpointDriver));
    }

    public function test_sqids_manager_driver_exists(): void
    {
        $this->app->config->set('sqids.drivers.url_endpoint', []);

        $sqidsManagerReflection = new ReflectionClass(SqidsManager::class);
        $driverExistsMethod = $sqidsManagerReflection->getMethod('driverExists');
        $driverExistsMethod->setAccessible(true);

        /** @var SqidsManager $sqidsManager */
        $sqidsManager = $this->app->get(SqidsManager::class);

        $this->assertTrue($driverExistsMethod->invoke($sqidsManager, 'url_endpoint'));
        $this->assertFalse($driverExistsMethod->invoke($sqidsManager, 'not_exists'));
    }
}
