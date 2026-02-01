<?php

declare(strict_types = 1);

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LoggableTrait
{
    /**
     * @param array<string, mixed> $extras
     */
    protected function logActivity(string $title, string $message, bool $sucesso, array $extras = []): void
    {
        $level = $sucesso ? 'info' : 'error';

        Log::channel('activity')->$level($title, array_merge([
            'Mensagem'   => $message,
            'user_id'    => Auth::id(),
            'user_email' => Auth::user()?->email,
            'tenant_id'  => Auth::user()?->tenant_id,
            'ip'         => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'timestamp'  => now()->toDateTimeString(),
        ], $extras));
    }
}
