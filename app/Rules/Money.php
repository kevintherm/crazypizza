<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Money implements ValidationRule
{
    protected int $precision;

    public function __construct(int $precision = 2)
    {
        $this->precision = $precision;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalized = str_replace(',', '', $value);

        $pattern = '/^\d+(\.\d{1,' . $this->precision . '})?$/';

        if (!preg_match($pattern, $normalized)) {
            $fail("The {$attribute} must be a valid decimal number with up to {$this->precision} decimal places.");
        }
    }
}
