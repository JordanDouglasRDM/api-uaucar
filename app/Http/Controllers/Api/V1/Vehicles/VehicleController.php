<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api\V1\Vehicles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicles\GetAllVehiclesRequest;
use App\Http\Requests\Vehicles\StoreVehiclesRequest;
use App\Http\Requests\Vehicles\UpdateVehiclesRequest;
use App\Http\Utilities\ResponseFormatter;
use App\Services\Vehicles\VehicleService;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $vehicleService
    ) {
    }

    public function store(StoreVehiclesRequest $request): JsonResponse
    {
        $serviceResponse = $this->vehicleService->store($request->validated());

        return ResponseFormatter::format($serviceResponse);
    }

    public function update(UpdateVehiclesRequest $request, int $id): JsonResponse
    {
        $serviceResponse = $this->vehicleService->update($request->validated(), $id);

        return ResponseFormatter::format($serviceResponse);
    }

    public function index(GetAllVehiclesRequest $request): JsonResponse
    {
        $serviceResponse = $this->vehicleService->getAll($request->validated());

        return ResponseFormatter::format($serviceResponse);
    }
}
