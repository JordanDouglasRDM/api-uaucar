<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\PasswordResetMail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Contracts\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements Auditable, JWTSubject
{
    use \OwenIt\Auditing\Auditable;

    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public const array LEVELS_ALLOWED = ['master', 'manager', 'operator', 'seller'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function sendPasswordResetNotification($token): void
    {
        $domain = $this->tenant->domain === 'localhost' ? 'localhost:5173' : $this->tenant->domain;
        $url = sprintf(
            '%s://%s/reset-password/%s?%s',
            app()->isProduction() ? 'https' : 'http',
            $domain,
            $token,
            http_build_query(['email' => $this->email])
        );

        $logoUrl = $this->tenant->logo ?? null;
        $firmName = $this->tenant->name;

        Mail::to($this->email)->send(new PasswordResetMail(
            $url,
            $logoUrl,
            $firmName
        ));
    }

    public function belongsToTenant(Tenant $tenant): bool
    {
        return $this->tenant_id === $tenant->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
