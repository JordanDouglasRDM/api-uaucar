<?php

declare(strict_types = 1);

namespace App\Http\Requests\Vehicles;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class GetAllVehiclesRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $model    = new Vehicle();
        $fillable = $model->getFillable();
        $in       = implode(',', $fillable);

        return [
            'per_page'        => 'required|integer|min:1',
            'order_by'        => 'required|string|in:' . $in,
            'order_direction' => 'required|string|in:asc,desc',
            'filters'         => 'nullable|array',
            'filters.status'  => 'nullable|string|in:' . implode(',', Vehicle::statusRouteEnum()),
            'filters.tipo'    => 'nullable|string|in:' . implode(',', Vehicle::tipoRouteEnum()),
            'filters.search'  => 'nullable|string|max:255',
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => 'Os dados fornecidos são inválidos!',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
