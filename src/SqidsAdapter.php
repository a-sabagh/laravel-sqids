<?php

namespace LaravelSqids;

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

    public function encode(...$numbers): string
    {
        $numbers = func_get_args();

        return parent::encode($numbers);
    }
}
