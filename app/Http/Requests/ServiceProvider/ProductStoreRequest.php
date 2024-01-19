<?php

namespace App\Http\Requests\ServiceProvider;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class ProductStoreRequest extends BaseRequest
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
            'provider_id'   => [
                'sometimes',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', UserType::SERVICE_PROVIDER->value);
                }),
            ],
            'title'     => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,gif|max:100',
            'price'    => 'required|numeric',
            'discount' => 'sometimes|numeric',
        ];
    }

    public function messages()
    {
        return [
            'provider_id.exists' => 'User must be existed as service-provider',
        ];
    }
}
