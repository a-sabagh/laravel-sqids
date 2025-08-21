<?php

use Illuminate\Support\Collection;
use LaravelSqids\Facades\Sqids;

function sqids(array $numbers, ?string $driver = null): string
{
    $sqids = Sqids::driver($driver);

    return $sqids->encode($numbers);
}

function unsqids(string $encodedString, ?string $driver = null): array
{
    $sqids = Sqids::driver($driver);

    return $sqids->decode($encodedString);
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
