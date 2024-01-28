<?php

namespace App\Rules;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;

class FutureDateTimeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $date;
    public function __construct($date)
    {
        $this->date = $date;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $selectedTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->date . ' ' . $value);
        if ($selectedTime->lessThan(now())) {
            $fail('The selected time is outdated');
        }
    }
}
