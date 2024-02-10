<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodStoreRequest extends FormRequest
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
            'card_type' => 'required|array',
        ];

        if ($this->has('card_type')) {
            foreach ($this->card_type as $index => $cardType) {
                $rules["card_type.$index"] = 'required|string|exists:payment_methods,name_en';
                $rules["cardholder_name.$index"] = "required_if:card_type.$index,$cardType|string";
                $rules["card_number.$index"] = "required_if:card_type.$index,$cardType|string";
                $rules["expiration_month.$index"] = "required_if:card_type.$index,$cardType|numeric";
                $rules["expiration_year.$index"] = "required_if:card_type.$index,$cardType|numeric";
                $rules["cvv.$index"] = "required_if:card_type.$index,$cardType|numeric";
                $rules["billing_address.$index"] = "required_if:card_type.$index,$cardType|string";
            }
        }
        return $rules;
    }
}
