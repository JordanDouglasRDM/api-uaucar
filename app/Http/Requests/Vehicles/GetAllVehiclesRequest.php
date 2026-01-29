<?php

declare(strict_types = 1);

namespace App\Http\Requests\Vehicles;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $model    = new Vehicle();
        $fillable = $model->getFillable();
        $in       = implode(',', $fillable);

        return [
            'per_page'        => 'required|integer|min:1',
            'order_by'        => "required|string|in:{$in}",
            'order_direction' => 'required|string|in:asc,desc',
            'filters'         => 'nullable|array',
            'filters.status'  => 'nullable|string|in:' . implode(',', Vehicle::statusRouteEnum()),
            'filters.tipo'    => 'nullable|string|in:' . implode(',', Vehicle::tipoRouteEnum()),
            'filters.search'  => 'nullable|string|max:255',
        ];
    }
}
