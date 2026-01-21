<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        $tipo       = $this->faker->randomElement(Vehicle::tipoRouteEnum());
        $precoVenda = $this->faker->randomFloat(2, 20000, 150000);

        return [
            'tenant_id'            => 1,
            'user_id'              => 1,
            'tipo'                 => $tipo,
            'status'               => $this->faker->randomElement(Vehicle::statusRouteEnum()),
            'marca'                => $this->faker->company(),
            'modelo'               => $this->faker->word(),
            'ano_fabricacao'       => $this->faker->year(),
            'ano_modelo'           => $this->faker->year(),
            'placa'                => strtoupper($this->faker->bothify('???-####')),
            'chassi'               => strtoupper($this->faker->bothify('*****************')),
            'cor'                  => $this->faker->safeColorName(),
            'quilometragem'        => $this->faker->numberBetween(0, 150000),
            'tipo_combustivel'     => $this->faker->randomElement(['Gasolina', 'Álcool', 'Flex', 'Diesel']),
            'transmissao'          => $this->faker->randomElement(['Manual', 'Automático']),
            'preco_compra'         => $precoVenda * 0.8,
            'preco_venda'          => $precoVenda,
            'codigo_fipe'          => $this->faker->numerify('######-#'),
            'valor_fipe'           => $precoVenda * 1.05,
            'descricao'            => $this->faker->sentence(),
            'atributos_adicionais' => $this->generateAttributes($tipo),
        ];
    }

    private function generateAttributes(string $tipo): array
    {
        return match ($tipo) {
            'carro' => [
                'portas' => $this->faker->randomElement([2, 4]),
                'airbag' => $this->faker->boolean(),
            ],
            'moto' => [
                'cilindradas'      => $this->faker->randomElement([125, 160, 250, 600, 1000]),
                'partida_eletrica' => $this->faker->boolean(),
            ],
            'caminhao' => [
                'eixos'            => $this->faker->numberBetween(2, 6),
                'capacidade_carga' => $this->faker->numberBetween(5, 40) . ' Ton',
            ],
            default => [],
        };
    }
}
