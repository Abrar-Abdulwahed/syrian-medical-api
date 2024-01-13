<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements Rule
{
    public function passes($attribute, $value)
    {
        $phoneNumber = preg_replace('/\D/', '', $value);

        // Check if the phone number starts with the valid Syrian country code
        $pattern = '/^(?:\+?963|00963)?([7-9]\d{8})$/';

        return preg_match($pattern, $phoneNumber);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid Syrian phone number.';
    }
}
