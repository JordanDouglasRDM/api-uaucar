<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResolveTenantMiddleware
{
    public function handle(Request $request, Closure $next): JsonResponse | Response
    {
        $tenant = Tenant::where('domain', $request->getHost())->first();

        if (! $tenant || ! $tenant->isActive()) {
            throw new UnauthorizedException('Tenant inativo ou inexistente.');
        }

        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
