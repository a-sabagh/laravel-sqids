<?php

namespace LaravelSqids\Tests;

use Illuminate\Support\Str;
use LaravelSqids\Facades\Sqids;
use PHPUnit\Framework\Attributes\DataProvider;

class SqidsTrackingCodeWithPadTest extends TestCase
{
    public static function fakeIdProvider4DigitNumber(): array
    {
        $cases = [];
        for ($i = 0; $i < 10; $i++) {
            $fakeNumber = fake()->numberBetween(1000, 9999);

            $cases["case#{$i}-{$fakeNumber}"] = [$fakeNumber];
        }

        return $cases;
    }

    #[DataProvider('fakeIdProvider4DigitNumber')]
    public function test_sqid_include_pad_using_tracking_code(int $id): void
    {
        $pad = str_shuffle((string) $id);
        $this->app->config->set('sqids.drivers.tracking_code.pad', $pad);

        $encodeString = Sqids::trackingCode()->encodeInteger($id);

        $this->assertTrue(
            str_starts_with($encodeString, $pad),
            "Pad prefix missing for id {$id}, got {$encodeString}"
        );
    }

    #[DataProvider('fakeIdProvider4DigitNumber')]
    public function test_sqid_decode_using_tracking_code_driver(int $id): void
    {
        $pad = '5000';
        $this->app->config->set('sqids.drivers.tracking_code.pad', $pad);

        $encodeString = Sqids::trackingCode()->encodeInteger($id);

        $decodeNumber = Sqids::trackingCode()->decodeInteger($encodeString);

        $this->assertSame(
            $id,
            $decodeNumber,
            "original {$id}, encode {$encodeString}, decode {$decodeNumber}"
        );
    }

    #[DataProvider('fakeIdProvider4DigitNumber')]
    public function test_sqid_decode_using_tracking_code_driver_with_empty_pad_string(int $id): void
    {
        $this->app->config->set('sqids.drivers.tracking_code.pad', '');

        $encodeString = Sqids::trackingCode()->encodeInteger($id);

        $decodeNumber = Sqids::trackingCode()->decodeInteger($encodeString);

        $this->assertSame(
            $id,
            $decodeNumber,
            "original {$id}, encode {$encodeString}, decode {$decodeNumber}"
        );
    }
}
