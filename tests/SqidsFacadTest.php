<?php

namespace LaravelSqids\Tests;

use LaravelSqids\Facades\Sqids;
use LaravelSqids\SqidsManager;
use LaravelSqids\SqidsServiceProvider;
use Orchestra\Testbench\TestCase;

class SqidsFacadTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SqidsServiceProvider::class,
        ];
    }

    public function test_sqids_facade_accessor_correctly_bind_to_manager(): void
    {
        $facadeRoot = Sqids::getFacadeRoot();

        $this->assertInstanceOf(SqidsManager::class, $facadeRoot);
    }

    public function test_sqids_encoder_accept_integer(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode($id);

        $this->assertIsString($encodeString);
    }

    public function test_sqids_encoder_min_length(): void
    {
        $expectedStringLength = 5;

        $this->app->config->set('sqids.drivers.default', [
            'length' => $expectedStringLength,
        ]);

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode($id);
        $actualStringLength = strlen($encodeString);

        $this->assertEquals($expectedStringLength, $actualStringLength);
    }

    public function test_sqids_encoder_with_numerical_format(): void
    {
        $this->app->config->set('sqids.drivers.default', [
            'alphabet' => '123456789',
        ]);

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode($id);

        $this->assertIsNumeric($encodeString);
    }
}
