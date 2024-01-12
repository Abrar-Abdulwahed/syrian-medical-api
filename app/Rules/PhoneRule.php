<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneNumber = preg_replace('/\D/', '', $value);

        // Check if the phone number starts with the valid Syrian country code
        $pattern = '/^(?:\+?963|00963)?([7-9]\d{8})$/';
        preg_match($pattern, $phoneNumber);
    }
}
