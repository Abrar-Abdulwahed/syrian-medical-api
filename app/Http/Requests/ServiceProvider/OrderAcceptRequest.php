<?php

namespace App\Http\Requests\ServiceProvider;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Http\Requests\BaseRequest;
use App\Models\ServiceReservation;
use Illuminate\Validation\Validator;

class OrderAcceptRequest extends BaseRequest
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
        return [];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $instance = $this->reservation->reservationable;
                $appointmentDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $instance->appointment_date . ' ' . $instance->appointment_time);

                if ($instance instanceof ServiceReservation && $appointmentDateTime->lessThan(now())) {
                    $validator->errors()->add(
                        'appointment_date',
                        'The appointment date and time are outdated!'
                    );
                }
            }
        ];
    }
}
