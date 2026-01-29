<?php

declare(strict_types = 1);

use App\Exceptions\UnauthorizedException;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\ResolveTenantMiddleware;
use App\Http\Utilities\ResponseFormatter;
use App\Http\Utilities\ServiceResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('api', [
            SubstituteBindings::class,
            'tenant',
        ]);

        $middleware->alias([
            'auth'   => Authenticate::class,
            'tenant' => ResolveTenantMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (
            AuthorizationException $e,
                                   $request
        ) {
            return ResponseFormatter::format(
                ServiceResponse::error(
                    throw: $e,
                    status: Response::HTTP_FORBIDDEN,
                    message: 'Esta ação não é autorizada.',
                )
            );
        });
        $exceptions->render(function (
            HttpExceptionInterface $e,
                                   $request
        ) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ResponseFormatter::format(
                    ServiceResponse::error(
                        throw: $e,
                        status: $e->getStatusCode(),
                        message: __($e->getMessage()) ?: 'Erro na requisição.',
                    )
                );
            }

            return null;
        });

        $exceptions->render(function (
            UnauthorizedException $e,
                                  $request
        ) {
            return ResponseFormatter::format(
                ServiceResponse::error(
                    throw: $e,
                    status: Response::HTTP_UNAUTHORIZED,
                    message: 'Esta ação não é autorizada.',
                )
            );
        });

    })
    ->create();
