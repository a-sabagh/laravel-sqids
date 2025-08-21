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

}
