<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends BaseModel
{
    use hasFactory;
    protected $fillable = [
        'domain',
        'name',
        'cnpj',
        'logo',
        'administrator_email',
        'administrator_phone',
        'responsible_name',
        'responsible_email',
        'responsible_phone',
        'options',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
