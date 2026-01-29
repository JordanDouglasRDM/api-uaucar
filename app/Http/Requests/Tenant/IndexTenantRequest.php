<?php

namespace App\Http\Requests\Tenant;

use App\Models\Tenant;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndexTenantRequest extends FormRequest
{
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
        $model    = new Tenant();
        $fillable = $model->getFillable();
        $in       = implode(',', $fillable);

        return [
            'per_page'        => 'required|integer|min:1',
            'order_by'        => "required|string|in:{$in}",
            'order_direction' => 'required|string|in:asc,desc',
            'filters'         => 'nullable|array',
            'filters.status'  => 'nullable|in:active,inactive',
            'filters.search'  => 'nullable|string|max:255',
        ];
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
