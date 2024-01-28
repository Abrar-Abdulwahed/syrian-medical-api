<?php

namespace App\Rules;

use App\Models\ServiceAvailability;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueServiceAvailabilityRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $date, $user;
    public function __construct($user, $date)
    {
        $this->date = $date;
        $this->user = $user;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $preExisting =
            $this->user->providerServices
            ->flatMap->availabilities
            ->where('date', $this->date)
            ->some(function ($availability) use ($value) {
                return in_array($value, json_decode($availability->times, true));
            });
        if ($preExisting) {
            $fail('You have this date and time before, please check unique time or another day!');
        }
    }
}
