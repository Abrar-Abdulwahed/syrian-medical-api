<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class PatientAccountRequest extends BaseRequest
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
        return [
            'firstname'     => 'required|string|between:2,12',
            'lastname'      => 'required|string|between:2,12',
            'email'         => 'required|email:rfc,dns|max:100|unique:users,email',
            'password'      => ['required', $this->passwordRules(), 'max:25', 'confirmed'],
        ];
    }
}
