<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;

class ResolveTenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = Tenant::where('domain', $request->getHost())->first();

        if (! $tenant || ! $tenant->isActive()) {
            throw new UnauthorizedException('Tenant inativo ou inexistente.');
        }

        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
