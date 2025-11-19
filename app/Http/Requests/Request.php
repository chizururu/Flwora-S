<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the message errors abstraction in each validation rule.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '*.required' => ':Attribute tidak boleh kosong',
            '*.email' => 'Format email yang dimasukkan tidak valid',
            '*.unique' => ':Attribute sudah terdaftar',
            '*.min' => ':Attribute minimal harus :min karakter',
            '*.max' => ':Attribute terlalu panjang, maksimum :max karakter',
            '*.same' => ':Attribute harus sama'
        ];
    }

    /**
     * Handle a failed validation request
     *
     * This method is triggered when validation fails.
     * It throws an @HttpResponseException containing a JSON payload
     * with:
     *  - status: false
     *  - message: validation error message
     *  - errors: the detailed validation errors containers, utilized for each text field in flutter (client)
     *
     * @param Validator $validator
     * @throws HttpResponseException
     * */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], Response::class::HTTP_UNPROCESSABLE_ENTITY));
    }

}
