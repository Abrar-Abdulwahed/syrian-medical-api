<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserActivationRequest extends FormRequest
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
        // I will need it when I activate/deactivate
        // but when accept/refuse it don not want this activated
        return [
            'activated' => 'sometimes|boolean'
        ];
    }

    public function after(): array
    {
        $id = $this->route('id');
        $user = User::findOrFail($id);
        return [
            function (Validator $validator) use($user) {
                if (!$user || $user->type === UserType::PATIENT->value) {
                    $validator->errors()->add('type', 'The user type must be service provider or supervisor, patient ALWAYS activated!');
                }
            },
        ];
    }
}
