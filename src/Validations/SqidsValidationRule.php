<?php

namespace LaravelSqids\Validations;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use LaravelSqids\Facades\Sqids;

class SqidsValidationRule implements ValidationRule
{
    public function __construct(
        public ?string $driver = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $driver = $this->driver;

        if (! Sqids::driver($driver)->encodedStringValid($value)) {
            $fail('The :attribute must be a valid Sqid.');
        }
    }
}
