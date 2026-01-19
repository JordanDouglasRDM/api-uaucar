<?php

namespace Database\Seeders;

use App\Models\User;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'Jordan Douglas',
            'email'    => 'jordandouglas8515@gmail.com',
            'level'    => 'master',
            'password' => Hash::make('#Password@123'),
        ]);

        User::factory()->create([
            'name'     => 'Samuel henrique',
            'email'    => 'test@example.com',
            'level'    => 'master',
            'password' => Hash::make('#Password@123'),
        ]);

        User::factory()->count(8)->create();
    }
}
