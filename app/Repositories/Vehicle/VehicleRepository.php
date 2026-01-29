<?php

declare(strict_types = 1);

namespace App\Repositories\Vehicle;

use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class VehicleRepository
{
    public function __construct(protected Vehicle $vehicle)
    {
    }

    /** * @param array<string, mixed> $filters
     * @return LengthAwarePaginator<int, Vehicle>
     */
    public function getAll(array $data): LengthAwarePaginator
    {
        $query = $this->vehicle->query()->select([
            'id',
            'tipo',
            'status',
            'marca',
            'modelo',
            'ano_fabricacao',
            'ano_modelo',
            'placa',
            'chassi',
            'cor',
            'quilometragem',
            'tipo_combustivel',
            'transmissao',
            'preco_compra',
            'preco_venda',
            'codigo_fipe',
            'valor_fipe',
            'descricao',
            'atributos_adicionais',
        ]);
        $query->where('tenant_id', Auth::user()->tenant_id);

        $filters = $data['filters'] ?? [];
        $query->when(isset($filters['status']), function ($q) use ($filters): void {
            $q->where('status', $filters['status']);
        });

        $query->when(isset($filters['tipo']), function ($q1) use ($filters): void {
            $q1->where('tipo', $filters['tipo']);
        });

        $query->when(isset($filters['search']), function ($q2) use ($filters): void {
            $q2
                ->where('marca', 'like', '%' . $filters['search'] . '%')
                ->orWhere('modelo', 'like', '%' . $filters['search'] . '%')
                ->orWhere('ano_fabricacao', 'like', '%' . $filters['search'] . '%')
                ->orWhere('ano_modelo', 'like', '%' . $filters['search'] . '%')
                ->orWhere('placa', 'like', '%' . $filters['search'] . '%')
                ->orWhere('chassi', 'like', '%' . $filters['search'] . '%')
                ->orWhere('cor', 'like', '%' . $filters['search'] . '%')
                ->orWhere('quilometragem', 'like', $filters['search'])
                ->orWhere('tipo_combustivel', 'like', '%' . $filters['search'] . '%')
                ->orWhere('transmissao', 'like', '%' . $filters['search'] . '%')
                ->orWhere('preco_compra', 'like', $filters['search'])
                ->orWhere('preco_venda', 'like', $filters['search'])
                ->orWhere('codigo_fipe', 'like', '%' . $filters['search'] . '%')
                ->orWhere('valor_fipe', 'like', $filters['search']);
        });

        return $query
            ->orderBy($data['order_by'], $data['order_direction'])
            ->paginate((int) $data['per_page'])
            ->onEachSide(1);
    }
}
