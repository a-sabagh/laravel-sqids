<?php

namespace LaravelSqids;

use Illuminate\Support\Facades\Facade;

class Sqids extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SqidsManager::class;
    }
}
