<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();

        $response = [
            'code'   => '422',
            'status' => 'failed',
            'message' => 'The given data was invalid.',
            'errors' => $errors,
        ];

        throw new \Illuminate\Validation\ValidationException($validator, response()->json($response, 422));
    }
}
