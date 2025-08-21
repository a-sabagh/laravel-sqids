<?php

namespace LaravelSqids\Tests;

use Illuminate\Support\Str;
use LaravelSqids\Facades\Sqids;

class SqidsEncryptionValidationTest extends TestCase
{
    public function test_sqids_encoded_string_is_invalid(): void
    {
        $randomString = Str::random(32);

        $this->assertFalse(Sqids::encodedStringValid($randomString));
    }

    public function test_sqids_encoded_string_is_valid(): void
    {
        $id = 12345;
        $encodedString = Sqids::encodeInteger($id);

        $this->assertTrue(Sqids::encodedStringValid($encodedString));
    }

    public function test_sqids_encoded_string_with_pad_is_valid(): void
    {
        $id = 12345;

        $this->app->config->set('sqids.drivers.default.pad', 'pad_');

        $encodedString = Sqids::encodeInteger($id);

        $this->assertTrue(Sqids::encodedStringValid($encodedString));
    }
}
