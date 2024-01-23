<?php

namespace App\Http\Requests\Patient;

use App\Rules\TimeRule;
use App\Models\Reservation;
use App\Models\ProviderService;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;
use App\Models\ServiceAvailability;
use Illuminate\Validation\Validator;

class ReservationStoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'latitude'         => 'required|numeric|between:-90,90',
            'longitude'        => 'required|numeric|between:-180,180',
            'payment_method'   => 'required|json',
        ];
        // Check if $item is an instance of ProviderService
        if ($this->item instanceof ProviderService) {
            $rules['appointment_date'] = 'required|date|after:now';
            $rules['appointment_time'] = [
                'required',
                Rule::when($this->filled('appointment_date'), ['date_format:H:i:s', new TimeRule()])
            ];
        }
        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->item instanceof ProviderService) {
                    $availability = ServiceAvailability::where([
                        'provider_service_id' => $this->item->id,
                        'date' => $this->appointment_date,
                    ])->first();

                    // validateAppointmentDate
                    if (!$availability) {
                        $validator->errors()->add(
                            'appointment_date',
                            'This date is not determined by the service provider for this service!'
                        );
                    } else {
                        // validateAppointmentTime
                        $rightTimes = json_decode($availability->times, true);

                        if (!in_array($this->appointment_time, $rightTimes)) {
                            $validator->errors()->add(
                                'appointment_time',
                                'This time is not determined by the service provider for the date: ' . $this->appointment_date . '!'
                            );
                        }
                    }
                }
            }
        ];
    }
}
