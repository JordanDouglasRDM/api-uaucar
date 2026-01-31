<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use App\Http\Utilities\ResponseFormatter;
use App\Http\Utilities\ServiceResponse;
use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Authenticate
{
    /**
     * Manipula a autenticação de requisições API.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guard = $guards[0] ?? 'api';
        $auth  = Auth::guard($guard);
        /** @var Tenant|null $tenant*/
        $tenant = $request->attributes->get('tenant');

        try {
            if (! $tenant) {
                throw new UnauthorizedException('Tenant não resolvido.');
            }

            if (! $auth->check()) {
                throw new UnauthorizedException('Usuário não autenticado.');
            }

            /** @var User $user*/
            $user = $auth->user();

            if (! $user->belongsToTenant($tenant)) {
                throw new UnauthorizedException('Usuário não pertence ao tenant.');
            }

            return $next($request);
        } catch (UnauthorizedException $e) {
            return $this->unauthorized($e, $e->getMessage());
        } catch (Throwable $e) {
            Log::channel('auth')->warning('Falha na autenticação', [
                'message' => $e->getMessage(),
                'tenant'  => $tenant->id ?? null,
                'user_id' => $user->id ?? null,
                'ip'      => $request->ip(),
                'path'    => $request->path(),
            ]);

            return $this->unauthorized(
                $e,
                'Houve um erro na autenticação, tente novamente mais tarde.'
            );
        }
    }

    /**
     * Resposta JSON padronizada para falhas de autenticação.
     */
    private function unauthorized(UnauthorizedException | Throwable $trace, string $message): JsonResponse
    {
        return ResponseFormatter::format(
            ServiceResponse::error(
                throw: $trace,
                status: Response::HTTP_UNAUTHORIZED,
                message: $message,
            )
        );
    }
}
