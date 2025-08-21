<?php

namespace LaravelSqids\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelSqids\SqidsManager;

class Sqids extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SqidsManager::class;
    }
}
