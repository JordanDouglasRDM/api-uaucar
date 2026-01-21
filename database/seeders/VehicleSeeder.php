<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::factory()->count(50)->create();

        Vehicle::factory()->count(10)->create([
            'tipo'                 => 'carro',
            'marca'                => 'Toyota',
            'modelo'               => 'Corolla',
            'atributos_adicionais' => [
                'portas'          => 4,
                'ar_condicionado' => true,
            ],
        ]);

        Vehicle::factory()->count(5)->create([
            'tipo'                 => 'moto',
            'marca'                => 'Honda',
            'atributos_adicionais' => [
                'cilindradas'   => 600,
                'tipo_corrente' => 'O-ring',
            ],
        ]);
    }
}
