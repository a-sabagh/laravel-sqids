<?php

namespace LaravelSqids;

use Illuminate\Support\Collection;
use Sqids\Sqids;

class SqidsAdapter extends Sqids
{
    final public const DEFAULT_PAD = '';

    public function __construct(
        protected string $alphabet = self::DEFAULT_ALPHABET,
        protected int $length = self::DEFAULT_MIN_LENGTH,
        protected array $blocklist = self::DEFAULT_BLOCKLIST,
        protected string $pad = self::DEFAULT_PAD,
    ) {
        parent::__construct(
            alphabet: $this->alphabet,
            minLength: $this->length,
            blocklist: $this->blocklist
        );
    }

    public function encode(array $numbers): string
    {
        $pad = $this->pad;
        $encodedString = parent::encode($numbers);

        return "{$pad}{$encodedString}";
    }

    public function decode(string $encodeString): array
    {
        $pad = $this->pad;

        $encodeString = substr($encodeString, strlen($pad));
    }

    public function encodeInteger(int $id): string
    {
        return $this->encode([$id]);
    }

    public function decodeInteger(string $encodeString): int
    {
        $decodeArray = $this->decode($encodeString);

        return current($decodeArray);
    }

    public function decodeCollect(string $encodeString): Collection
    {
        return collect($this->decode($encodeString));
    }

    public function encodedStringValid(string $encodedString): bool
    {
        return $this->encodedStringAlphabetFairlyValid($encodedString)
            && $this->encodedStringMinLengthDefinitiveValid($encodedString)
            && $this->encodedStringPadDefinitiveValid($encodedString);
    }

    private function encodedStringAlphabetFairlyValid(string $encodedString): bool
    {
        $strippedEncodedString = substr($encodedString, strlen($this->pad));

        $allowedChars = str_split($this->alphabet);
        $strippedEncodedStringChars = str_split($strippedEncodedString);

        return empty(array_diff($strippedEncodedStringChars, $allowedChars));
    }

    private function encodedStringMinLengthDefinitiveValid(string $encodedString): bool
    {
        return strlen($encodedString) >= ($this->length + strlen($this->pad));
    }

    private function encodedStringPadDefinitiveValid(string $encodedString): bool
    {
        return str_starts_with($encodedString, $this->pad);
    }
}
