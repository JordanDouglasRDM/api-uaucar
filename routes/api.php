<?php

declare(strict_types = 1);

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\Vehicles\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('api')
    ->group(function (): void {
        Route::prefix('auth')->group(function (): void {
            /** Rotas sem Autenticação*/
            Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
            Route::post('/login', [AuthController::class, 'login']);

            Route::post('/forgot-password', [AuthController::class, 'passwordForgot']); //envia e-mail
            Route::post('/reset-password', [AuthController::class, 'passwordReset']); //atualiza senha guest

            /** Rotas com Autenticação via token*/
            Route::middleware('auth:api')->group(function (): void {
                Route::get('/me', [AuthController::class, 'me']);
                Route::post('/logout', [AuthController::class, 'logout']);
                Route::post('/password/reset', [AuthController::class, 'updatePassword']); //atualiza senha auth
            });
        });
        Route::middleware(['auth:api', 'can:manage-system'])->group(function (): void {
            Route::resource('tenants', TenantController::class)
                ->except(['create', 'edit']);

            Route::resource('vehicles', VehicleController::class)
                ->only(['index', 'store', 'update']);
        });
    });
