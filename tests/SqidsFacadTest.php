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

    public function test_sqids_encoder_min_length(): void
    {
        $expectedStringLength = 5;

        $this->app->config->set('sqids.drivers.default', [
            'length' => $expectedStringLength,
        ]);

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode([$id]);
        $actualStringLength = strlen($encodeString);

        $this->assertEquals($expectedStringLength, $actualStringLength);
    }

    public function test_sqids_encoder_with_numerical_format(): void
    {
        $this->app->config->set('sqids.drivers.default', [
            'alphabet' => '123456789',
        ]);

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encode([$id]);

        $this->assertIsNumeric($encodeString);
    }

    public function test_sqids_tracking_code_driver_formating(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::driver('tracking_code')->encode([$id]);

        $this->assertIsNumeric($encodeString);
    }

    public function test_sqids_tracking_coder_driver_with_magic_method(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::trackingCode()->encode([$id]);

        $this->assertIsNumeric($encodeString);
    }

    public function test_sqids_url_endpoint_driver_formating(): void
    {
        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::driver('url_endpoint')->encode([$id]);

        $this->assertIsString($encodeString);
        $this->assertNotEmpty($encodeString);
    }

    public function test_sqids_on_the_fly_driver_magic_call(): void
    {
        $this->app->config->set('sqids.drivers.custom_driver', [
            'alphabet' => '123456789',
        ]);

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::customDriver()->encode([$id]);

        $this->assertIsString($encodeString);
        $this->assertNotEmpty($encodeString);
    }

    public function test_sqids_decode_validation(): void
    {
        $this->app->config->set('sqids.drivers.default.pad', '');

        $id = fake()->numberBetween(10, 1000);

        $encodeString = Sqids::encodeInteger($id);

        $decodeNumber = Sqids::decodeInteger($encodeString);

        $this->assertSame($id, $decodeNumber);
    }
}
