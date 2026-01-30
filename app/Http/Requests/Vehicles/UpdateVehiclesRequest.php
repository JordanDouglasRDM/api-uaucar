<?php

declare(strict_types=1);

namespace App\Http\Requests\Vehicles;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class UpdateVehiclesRequest extends FormRequest
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
        // Recuperamos o ID do veículo da rota para a regra de ignorar unique
            $this->route('vehicle')->id ?? $this->route('vehicle');

        $tipoRouteEnum = implode(',', Vehicle::tipoRouteEnum());
        $statusRouteEnum = implode(',', Vehicle::statusRouteEnum());

        return [
            'tipo'   => "required|string|in:{$tipoRouteEnum}",
            'status' => "nullable|string|in:{$statusRouteEnum}",

            'marca'          => 'required|string|max:100',
            'modelo'         => 'required|string|max:100',
            'ano_fabricacao' => 'required|digits:4|integer',
            'ano_modelo'     => 'required|digits:4|integer',
            'placa'          => 'nullable|string|unique:vehicles,placa',
            'chassi'         => 'nullable|string|unique:vehicles,chassi',
            'cor'            => 'nullable|string|max:50',
            'quilometragem'  => 'required|required|integer|min:0',

            'tipo_combustivel' => 'required|required|string',
            'transmissao'      => 'required|required|string',

            'preco_venda'  => 'required|required|numeric|min:0',
            'preco_compra' => 'nullable|numeric|min:0',
            'codigo_fipe'  => 'nullable|string',
            'valor_fipe'   => 'nullable|numeric|min:0',

            'descricao'            => 'nullable|string',
            'atributos_adicionais' => 'nullable|array',
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
