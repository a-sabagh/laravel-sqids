<?php

namespace LaravelSqids\Tests;

use Illuminate\Support\Str;
use LaravelSqids\Facades\Sqids;
use Illuminate\Support\Facades\Validator;
use LaravelSqids\Validations\SqidsValidationRule;

class SqidsValidationRuleTest extends TestCase
{
    public function test_default_sqids_validation_rule(): void
    {
        $id = fake()->numberBetween(10, 99999);

        $validator = Validator::make(
            ['endpoint' => Sqids::encodeInteger($id)],
            ['endpoint' => [new SqidsValidationRule]]
        );

        $this->assertTrue($validator->passes());

        $validator = Validator::make(
            ['endpoint' => Str::random(32)],
            ['endpoint' => [new SqidsValidationRule]]
        );

        $this->assertFalse($validator->passes());
    }

    public function test_custom_driver_sqids_validation_rule(): void
    {
        $this->app->config->set('sqids.drivers.custom_driver', [
            'length' => 12,
            'pad' => '5000',
        ]);

        $id = fake()->numberBetween(10, 99999);

        $validator = Validator::make(
            ['endpoint' => Sqids::driver('custom_driver')->encodeInteger($id)],
            ['endpoint' => [new SqidsValidationRule('custom_driver')]]
        );

        $this->assertTrue($validator->passes());

        $validator = Validator::make(
            ['endpoint' => Sqids::encodeInteger($id)],
            ['endpoint' => [new SqidsValidationRule('custom_driver')]]
        );

        $this->assertFalse($validator->passes());
    }
}
