<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\BaseRequest;

class PictureStoreRequest extends BaseRequest
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
            'picture' => 'required|file|mimes:jpeg,jpg,png,gif|max:100',
        ];
    }
}