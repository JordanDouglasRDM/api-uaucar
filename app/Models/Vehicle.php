<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
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
}
