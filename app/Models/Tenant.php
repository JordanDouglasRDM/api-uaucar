<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'name',
        'status',
        'cnpj',
        'logo',
        'administrator_email',
        'administrator_phone',
        'responsible_name',
        'responsible_email',
        'responsible_phone',
        'options',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
