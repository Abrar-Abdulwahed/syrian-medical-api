<?php

namespace App\Http\Requests\ServiceProvider;

use App\Http\Requests\BaseRequest;

class ProductUpdateRequest extends BaseRequest
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
            'title_en'     => 'required|string',
            'title_ar'     => 'required|string',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:100',
            'price'    => 'required|numeric|gt:0',
            'discount' => 'sometimes|numeric|gte:0',
        ];
    }

    public function messages()
    {
        return [
            'provider_id.exists' => 'User must be existed as service-provider',
        ];
    }
}
