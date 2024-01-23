<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $selectedTime = Carbon::createFromFormat('Y-m-d H:i:s', $value);
        if ($selectedTime === false) {
            $fail('Invalid time format');
        } elseif ($selectedTime->lessThan(now())) {
            $fail('The selected time is outdated');
        }
    }
}
