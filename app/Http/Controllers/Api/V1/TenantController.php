<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\IndexTenantRequest;
use App\Http\Utilities\ResponseFormatter;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(protected TenantService $tenantService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTenantRequest $request): JsonResponse
    {
        $data = $request->validated();
        $serviceResponse = $this->tenantService->index($data);
        return ResponseFormatter::format($serviceResponse);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
