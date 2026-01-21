<?php

declare(strict_types = 1);

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PasswordResetUnauthenticatedRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|confirmed',
        ];
    }

    #[\Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => 'Os dados fornecidos são inválidos',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
