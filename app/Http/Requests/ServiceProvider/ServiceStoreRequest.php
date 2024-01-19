<?php

namespace App\Http\Requests\ServiceProvider;

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
        return [
            'provider_id'  => 'required|exists:users,id',
            'service_id'    => [
                'required',
                'exists:services,id',
                Rule::unique('provider_service', 'service_id')->where(function ($query) {
                    return $query->where('provider_id', $this->user()->id);
                }),
            ],
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'discount'      => 'sometimes|numeric',
            'dates'         => 'required|array',
            'dates.*'       => 'required|date_format:Y-m-d',
            'times'         => 'required|array',
            'times.*.*'     => 'required|string',
        ];
    }
}
