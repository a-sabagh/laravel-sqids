<?php

namespace LaravelSqids\Tests;

use LaravelSqids\Facades\Sqids;
use LaravelSqids\Tests\TestCase;

class SqidsWithPadTest extends TestCase 
{
    public function test_sqid_include_pad_at_right(): void
    {
        $id = fake()->numberBetween(1000,9999);
        $pad = '5000';
        
        $this->app->config->set('sqids.tracking_code.pad', $pad);

        $encodeString = Sqids::trackingCode()->encodeInteger($id);
        
        $this->assertTrue(str_starts_with($encodeString, $pad));

        $decodeNumber = Sqids::trackingCode()->decodeInteger($encodeString);

        $this->assertSame($decodeNumber, $id);
    }
}
