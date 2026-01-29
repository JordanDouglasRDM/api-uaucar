<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->resource->id,
            'marca'          => $this->resource->marca,
            'modelo'         => $this->resource->modelo,
            'ano_fabricacao' => $this->resource->ano_fabricacao,
            'ano_modelo'     => $this->resource->ano_modelo,
            'preco_venda'    => (float) $this->resource->preco_venda,
            'quilometragem'  => $this->resource->quilometragem,
            'tipo'           => $this->resource->tipo,
            'status'         => $this->resource->status,
        ];
    }
}
