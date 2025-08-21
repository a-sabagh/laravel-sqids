<?php

namespace LaravelSqids\Tests;

use LaravelSqids\Facades\Sqids;
use LaravelSqids\SqidsManager;
use Orchestra\Testbench\TestCase;

class SqidsFacadTest extends TestCase
{
    public function test_sqids_facade_accessor_correctly_bind_to_manager(): void
    {
        $facadeRoot = Sqids::getFacadeRoot();

        $this->assertInstanceOf(SqidsManager::class, $facadeRoot);
    }
}
