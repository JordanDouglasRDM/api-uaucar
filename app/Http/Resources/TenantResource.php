<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TenantResource extends JsonResource
{
    use HelperTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->resource->id,
            'domain' => $this->resource->domain,
            'name'   => $this->resource->name,
            'status' => $this->resource->status,
            'cnpj'   => $this->resource->cnpj,
            'logo'   => $this->resource->logo ? Storage::url(
                $this->resource->logo
            ) : null,
            'administrator_email' => $this->resource->administrator_email,
            'administrator_phone' => $this->resource->administrator_phone,
            'responsible_name'    => $this->resource->responsible_name,
            'responsible_email'   => $this->resource->responsible_email,
            'responsible_phone'   => $this->resource->responsible_phone,
            'options'             => $this->resource->options,
            'users_count'         => $this->resource->users_count,
            'created_at'          => $this->formatDate(
                $this->resource->created_at?->toDateTimeString()
            ),
            'updated_at' => $this->formatDate(
                $this->resource->updated_at?->toDateTimeString()
            ),
        ];
    }
}
