<?php

namespace App\Http\Requests;

use App\Rules\PhoneRule;
use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class SupervisorStoreRequest extends FormRequest
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
            // 'username'  => 'required|string|between:2,24',
            // 'email'     => 'required|email:rfc,dns|max:100|unique:users,email',
            // 'phone'     => ['required', new PhoneRule],
        ];
    }
}
