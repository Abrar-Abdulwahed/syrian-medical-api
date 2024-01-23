<?php

namespace App\Http\Requests\ServiceProvider;

use App\Models\Admin;
use App\Enums\UserType;
use App\Rules\TimeRule;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class ServiceStoreRequest extends BaseRequest
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
        $user = $this->user();

        $serviceIdRules = [
            'required',
            'exists:services,id',
            Rule::unique('provider_service', 'service_id')->where(function ($query) use ($user) {
                if ($user instanceof Admin) {
                    return $query->where('provider_id', $this->provider_id);
                } else {
                    return $query->where('provider_id', $user->id);
                }
            }),
        ];

        $rules = [
            'provider_id'   => [
                'sometimes',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', UserType::SERVICE_PROVIDER->value);
                }),
            ],
            'service_id'    => $serviceIdRules,
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'discount'      => 'sometimes|numeric',
            'dates'         => 'required|array',
        ];
        if ($this->has('dates'))
            foreach ($this->input('dates') as $index => $date) {
                $rules["dates.$index"] = "required|date|date_format:Y-m-d|after_or_equal:now";
                $rules["times.$index"] = "required_with:dates.$index";
                $rules["times.$index.*"] = "date_format:H:i:s";
            }

        return $rules;
    }
}
