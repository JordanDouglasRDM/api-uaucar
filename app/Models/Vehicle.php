<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tenant_id',
        'user_id',
        'tipo',
        'status',
        'marca',
        'modelo',
        'ano_fabricacao',
        'ano_modelo',
        'placa',
        'chassi',
        'cor',
        'quilometragem',
        'tipo_combustivel',
        'transmissao',
        'preco_compra',
        'preco_venda',
        'codigo_fipe',
        'valor_fipe',
        'descricao',
        'atributos_adicionais',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'atributos_adicionais' => 'array',
        'preco_compra'         => 'decimal:2',
        'preco_venda'          => 'decimal:2',
        'valor_fipe'           => 'decimal:2',
    ];

    /** @return array<int, string> */
    public static function tipoRouteEnum(): array
    {
        return ['carro', 'moto', 'caminhao', 'utilitario', 'outro'];
    }

    /** @return array<int, string> */
    public static function statusRouteEnum(): array
    {
        return ['disponivel', 'vendido', 'preparacao', 'reservado'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
