<?php

declare(strict_types = 1);

namespace App\Services\Vehicles;

use App\Http\Utilities\ServiceResponse;
use App\Models\Vehicle;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class VehicleService
{
    public function __construct(protected Vehicle $vehicle)
    {
    }

    /** @param array<string, mixed> $data */
    public function store(array $data): ServiceResponse
    {
        try {
            $data['tenant_id'] = Auth::user()->tenant_id;
            $data['user_id']   = Auth::id();

            Vehicle::create($data);

            return ServiceResponse::success(
                message: 'Veículo cadastrado com sucesso!',
                status: 201
            );
        } catch (ModelNotFoundException $e) {
            return ServiceResponse::error($e, 404, 'Recurso não encontrado.');
        } catch (Exception $e) {
            return ServiceResponse::error($e, 500, $e->getMessage());
        }
    }

    /** @param array<string, mixed> $data */
    public function update(array $data, int $id): ServiceResponse
    {
        try {
            $vehicle = Vehicle::where('tenant_id', Auth::user()->tenant_id)
                ->findOrFail($id);
            $vehicle->update($data);

            return ServiceResponse::success(
                ['vehicle' => $vehicle->id],
                message: 'Veículo atualizado com sucesso!'
            );
        } catch (ModelNotFoundException $e) {
            return ServiceResponse::error($e, 404, 'Recurso não encontrado.');
        } catch (Exception $e) {
            return ServiceResponse::error($e, 500, $e->getMessage());
        }
    }
}
