<?php

namespace LaravelSqids\Tests;

use Illuminate\Support\Collection;
use LaravelSqids\Facades\Sqids;

class SqidsHelpersTest extends TestCase
{
    public function test_sqids_encodes_array_to_string(): void
    {
        $this->app->config->set('sqids.drivers.default.pad', '');

        $id = fake()->numberBetween(10, 1000);
        $encoded = sqids([$id]);

        $this->assertIsString($encoded);
        $this->assertNotEmpty($encoded);
    }

    public function test_unsqids_decodes_string_to_array(): void
    {
        $this->app->config->set('sqids.drivers.default.pad', '');

        $id = fake()->numberBetween(10, 1000);
        $encoded = Sqids::encode([$id]); // generate a valid encoded string

        $decoded = unsqids($encoded);

        $this->assertIsArray($decoded);
        $this->assertSame([$id], $decoded);
    }

    public function test_roundtrip_array_encode_decode_collect(): void
    {
        $this->app->config->set('sqids.drivers.default.pad', '');

        $id = fake()->numberBetween(10, 1000);
        $encoded = Sqids::encode([$id]);

        $collect = unsqidsCollect($encoded);

        $this->assertInstanceOf(Collection::class, $collect);
        $this->assertSame([$id], $collect->toArray());
    }

    public function test_unsqids_int_decodes_integer_from_encoded_string_generated_by_encode_array_flow(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodedString = Sqids::encode([$id]);

        $decodedInt = unsqidsInt($encodedString);

        $this->assertSame($id, $decodedInt);
    }

    public function test_helpers_are_autoloaded(): void
    {
        $this->assertTrue(function_exists('sqids'), 'Global helper sqids() is not loaded. Ensure composer.json autoload.files includes the helpers file and run composer dump-autoload.');
        $this->assertTrue(function_exists('unsqids'), 'Global helper unsqids() is not loaded.');
        $this->assertTrue(function_exists('sqidsInt'), 'Global helper sqidsInt() is not loaded.');
        $this->assertTrue(function_exists('unsqidsInt'), 'Global helper unsqidsInt() is not loaded.');
        $this->assertTrue(function_exists('unsqidsCollect'), 'Global helper unsqidsCollect() is not loaded.');
    }
}
