<?php

use Illuminate\Support\Collection;
use LaravelSqids\Facades\Sqids;

function sqids(int $id, ?string $driver = null): string
{
    $sqids = Sqids::driver($driver);

    return $sqids->encode($id);
}

function unsqids(int $id, ?string $driver = null): string
{
    $sqids = Sqids::driver($driver);

    return $sqids->decode($id);
}

function sqidsInt(int $id, ?string $driver = null): int
{
    $sqids = Sqids::driver($driver);

    return $sqids->encodeInteger($id);
}

function unsqidsInt(string $encodedString, ?string $driver = null): int
{
    $sqids = Sqids::driver($driver);

    return $sqids->decodeInteger($encodedString);
}

function unsqidsCollect(string $encodedString, ?string $driver = null): Collection
{
    $sqids = Sqids::driver($driver);

    return $sqids->decodeCollect($encodedString);
}
