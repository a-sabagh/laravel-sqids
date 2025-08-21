<?php

namespace LaravelSqids\Tests;

use Illuminate\Support\Collection;
use LaravelSqids\Facades\Sqids;

class SqidsEncodeCompositionTest extends TestCase
{
    public function test_sqids_adapter_collect_structure(): void
    {
        $this->app->config->set('sqids.drivers.default.pad', '');

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode([$id]);

        $decodeCollect = Sqids::decodeCollect($encodeString);

        $this->assertInstanceOf(Collection::class, $decodeCollect);
        $this->assertSame($decodeCollect->toArray(), [$id]);
    }

    public function test_sqids_encode_integer_accept_integer(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encodeInteger($id);

        $this->assertIsString($encodeString);
    }

    public function test_sqids_decode_integer(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encodeInteger($id);

        $decodeInteger = Sqids::decodeInteger($encodeString);

        $this->assertSame($id, $decodeInteger);
    }

    public function test_sqids_decode_integer_by_encode_action(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode([$id]);

        $decodeInteger = Sqids::decodeInteger($encodeString);

        $this->assertSame($id, $decodeInteger);
    }
}
