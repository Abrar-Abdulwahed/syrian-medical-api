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
    protected $date, $user, $ignore;
    public function __construct($user, $date, $ignore = null)
    {
        $this->date = $date;
        $this->user = $user;
        $this->ignore = $ignore;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $preExisting =
            $this->user->providerServices
            ->flatMap->availabilities
            ->where('date', $this->date)
            ->reject(function ($availability) {
                return $this->ignore === $availability->providerService->id;
            })
            ->some(function ($availability) use ($value) {
                return in_array($value, json_decode($availability->times, true));
            });
        if ($preExisting) {
            $fail(__('message.date_and_time_found'));
        }
    }
}
