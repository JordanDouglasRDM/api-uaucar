<?php

declare(strict_types = 1);

namespace App\Http\Requests\Tenant;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTenantRequest extends FormRequest
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
            'domain'              => 'required|unique:tenants,domain|string|max:50',
            'name'                => 'required|string|max:255',
            'status'              => 'required|in:active,inactive',
            'cnpj'                => 'required|unique:tenants,cnpj|min:14|max:14',
            'logo'                => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'administrator_email' => 'nullable|email|unique:tenants,administrator_email',
            'administrator_phone' => 'nullable|string|max:11',
            'responsible_name'    => 'nullable|string|max:255',
            'responsible_email'   => 'nullable|email|unique:tenants,responsible_email',
            'responsible_phone'   => 'nullable|string|max:11',
            'options'             => 'nullable|json',
        ];
    }

    /**
     * @return array<string, mixed>
    */
    #[\Override]
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'id' => $this->route('tenant'),
        ]);
    }

    #[\Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => 'Os dados fornecidos são inválidos.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
