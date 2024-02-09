<?php

namespace App\Http\Requests\Patient;

use App\Enums\OrderStatus;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Validator;

class ReservationDestroyRequest extends BaseRequest
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
                $instance = $this->reservation;
                $locale = app()->getLocale();
                if ($instance->status !== OrderStatus::PENDING->value) {
                    $validator->errors()->add(
                        'status',
                        __('message.cant_cancel_order') . $instance->getStatusLabel($instance->status, $locale) . ' by provider!'
                    );
                }
            }
        ];
    }
}
