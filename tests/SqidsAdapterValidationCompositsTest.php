<?php

namespace LaravelSqids\Tests;

use LaravelSqids\SqidsAdapter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SqidsAdapterValidationCompositsTest extends TestCase
{
    protected $sqidsAdapter;

    protected $alphabet = 'abcxyz';

    protected $minLength = 4;

    protected $blocklist = [];

    protected $pad = 'x';

    protected function setUp(): void
    {
        parent::setUp();

        $this->sqidsAdapter = new SqidsAdapter(
            alphabet: $this->alphabet,
            length: $this->minLength,
            blocklist: $this->blocklist,
            pad: $this->pad,
        );
    }

    public static function alphabetFairlyValidProvider(): array
    {
        return [
            'padAllInAlphabet' => ['xabc', true],
            'padZCharsOk' => ['xazz', true],
            'padInvalidDigit' => ['xabc1', false],
            'onlyPad' => ['x', true],
            'noPadButLetters' => ['yabc', true],
            'nonAllowedLetter' => ['xq', false],
        ];
    }

    #[DataProvider('alphabetFairlyValidProvider')]
    public function test_encoded_string_alphabet_fairly_valid(string $encoded, bool $expected): void
    {
        $adapter = $this->sqidsAdapter;
        $ref = new ReflectionClass($adapter);
        $method = $ref->getMethod('encodedStringAlphabetFairlyValid');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($adapter, $encoded));
    }

    public static function minLengthDefinitiveValidProvider(): array
    {
        return [
            'justUnder' => ['xabc', false],
            'exact' => ['xabcd', true],
            'longer' => ['xabcdef', true],
            'onlyPad' => ['x', false],
            'empty' => ['', false],
        ];
    }

    #[DataProvider('minLengthDefinitiveValidProvider')]
    public function test_encoded_string_min_length_definitive_valid(string $encoded, bool $expected): void
    {
        $adapter = $this->sqidsAdapter;
        $ref = new ReflectionClass($adapter);
        $method = $ref->getMethod('encodedStringMinLengthDefinitiveValid');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($adapter, $encoded));
    }

    #[DataProvider('padDefinitiveValidProvider')]
    public function test_encoded_string_pad_definitive_valid(string $encoded, bool $expected): void
    {
        $adapter = $this->sqidsAdapter;
        $ref = new ReflectionClass($adapter);
        $method = $ref->getMethod('encodedStringPadDefinitiveValid');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($adapter, $encoded));
    }

    public static function padDefinitiveValidProvider(): array
    {
        return [
            'startsWithPad' => ['xabc', true],
            'doublePad' => ['xxabc', true],
            'padOnly' => ['x', true],
            'doesNotStartWith' => ['yabc', false],
            'empty' => ['', false],
        ];
    }

    public static function encodedStringValidProvider(): array
    {
        return [
            'allConditionsPass' => ['xabcz', true,  'OK: pad yes, len 5, letters ok'],
            'failsAlphabet' => ['xabc1', false, 'Contains digit after pad'],
            'failsPad' => ['yabcz', false, 'Does not start with pad'],
            'failsLength' => ['xabc',  false, 'Too short'],
            'padOnly' => ['x',     false, 'Too short and empty body'],
            'longValid' => ['xxyzzabc', true, 'Pad ok, length ok, letters ok'],
            'invalidLetterQ' => ['xabqz', false, 'q not in alphabet'],
        ];
    }

    #[DataProvider('encodedStringValidProvider')]
    public function test_encoded_string_valid(string $encoded, bool $expected, string $case): void
    {
        $adapter = $this->sqidsAdapter;
        $this->assertSame($expected, $adapter->encodedStringValid($encoded), $case);
    }
}
