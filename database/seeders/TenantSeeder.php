<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::factory()->create([
            'domain'  => config('app.host'),
            'name'    => config('app.name'),
            'options' => '{}',
        ]);

        Tenant::factory()->create([
            'domain'  => 'cliente.' . config('app.host'),
            'name'    => 'Primeiro Cliente' . config('app.name'),
            'options' => '{}',
        ]);
    }
}
