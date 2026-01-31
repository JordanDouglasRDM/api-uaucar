<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Resources\TenantResource;
use App\Http\Utilities\ServiceResponse;
use App\Repositories\TenantRepository;
use App\Traits\HandleImageTrait;
use App\Traits\HelperTrait;
use Throwable;

class TenantService
{
    use HelperTrait;
    use HandleImageTrait;

    public function __construct(protected TenantRepository $repository)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function index(array $data): ServiceResponse
    {
        try {
            $result      = $this->repository->index($data);
            $message     = $result->total() > 0 ? 'Registros encontrados' : 'Nenhum registro encontrado';
            $resultItems = TenantResource::collection($result);
            $result      = $this->toPaginator($result, $resultItems);

            return ServiceResponse::success($result, $message);
        } catch (Throwable $throwable) {
            return ServiceResponse::error($throwable);
        }
    }
}
