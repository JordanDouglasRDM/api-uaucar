<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\IndexTenantRequest;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Http\Requests\Tenant\UpdateTenantRequest;
use App\Http\Utilities\ResponseFormatter;
use App\Services\TenantService;
use App\Traits\LoggableTrait;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    use LoggableTrait;

    public function __construct(protected TenantService $tenantService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTenantRequest $request): JsonResponse
    {
        $data            = $request->validated();
        $serviceResponse = $this->tenantService->index($data);

        return ResponseFormatter::format($serviceResponse);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreTenantRequest $request): JsonResponse
    {
        $data     = $request->validated();
        $logoFile = $request->file('logo');
        unset($data['logo']);

        $serviceResponse = $this->tenantService->store($data, $logoFile);

        $this->logActivity(
            'Cadastro de Tenant',
            $serviceResponse->message,
            $serviceResponse->success,
            [
                'payload' => $data,
                'tenant'  => $serviceResponse->data,
            ]
        );

        if (app()->isProduction()) {
            $serviceResponse->data = null;
        }

        return ResponseFormatter::format($serviceResponse);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request): JsonResponse
    {
        $data     = $request->validated();
        $logoFile = $request->file('logo');
        $id       = (int)$data['id'];
        unset($data['id'], $data['logo']);

        $serviceResponse = $this->tenantService->update($data, $logoFile, $id);

        $this->logActivity(
            'Atualização de Tenant',
            $serviceResponse->message,
            $serviceResponse->success,
            [
                'payload' => $data,
                'tenant'  => $serviceResponse->data,
            ]
        );

        if (app()->isProduction()) {
            $serviceResponse->data = null;
        }

        return ResponseFormatter::format($serviceResponse);
    }
}
