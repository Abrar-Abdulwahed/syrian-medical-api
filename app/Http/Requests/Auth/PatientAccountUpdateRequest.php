<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class PatientAccountUpdateRequest extends BaseRequest
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
            'firstname'     => 'sometimes|required|string|between:2,12',
            'lastname'      => 'sometimes|required|string|between:2,12',
            'username'      => 'sometimes|required|string|between:2,24',
            'email'         => 'sometimes|required|email:rfc|max:100|unique:users,email,' . $userId,
            'password'      => ['sometimes', 'required', $this->passwordRules(), 'max:25', 'confirmed'],
        ];
    }
}
