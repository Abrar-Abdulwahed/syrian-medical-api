<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class ServiceProviderAccountRequest extends BaseRequest
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
        $userId = $this->user()?->id;
        return [
            'firstname'     => 'required|string|between:2,12',
            'lastname'      => 'required|string|between:2,12',
            'username'      => 'required|string|between:2,24',
            'email'         => 'required|email:rfc,dns|max:100|unique:users,email,' . $userId,
            'password'      => ['sometimes', 'required', $this->passwordRules(), 'max:25', 'confirmed'],
            'bank_name'     => 'required|string|max:100',
            'iban_number'   => 'required|alpha_dash|max:34',
            'swift_code'    => [
                'sometimes',
                'required',
                'regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
                'max:11',
            ],
            'evidence'      => 'required|file|mimes:pdf|max:2048',
        ];
    }
}
