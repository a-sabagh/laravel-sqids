<?php

namespace LaravelSqids\Tests;

use LaravelSqids\SqidsServiceProvider;
use Orchestra\Testbench\TestCase as TestBench;

class TestCase extends TestBench
{
    protected function getPackageProviders($app)
    {
        return [
            SqidsServiceProvider::class,
        ];
    }
}
