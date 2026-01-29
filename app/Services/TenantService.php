<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Resources\TenantResource;
use App\Http\Utilities\ServiceResponse;
use App\Repositories\TenantRepository;
use App\Traits\HelperTrait;
use Throwable;

class TenantService
{
    use HelperTrait;

    public function __construct(protected TenantRepository $repository)
    {
    }

    public function index(array $data): ServiceResponse
    {
        try {
            $result      = $this->repository->index($data);
            $message     = $result->total() > 0 ? 'Registros encontrados' : 'Nenhum registro encontrado';
            $resultItems = TenantResource::collection($result);
            $result      = $this->toPaginator($result, $resultItems);

            return ServiceResponse::success($result, $message);
        } catch (Throwable $e) {
            return ServiceResponse::error($e);
        }
    }
}
