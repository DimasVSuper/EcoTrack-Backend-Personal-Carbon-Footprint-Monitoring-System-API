<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\TransportType::insert([
            ['name' => 'Mobil Bensin', 'emission_factor_per_km' => 0.192],
            ['name' => 'Mobil Listrik', 'emission_factor_per_km' => 0.053],
            ['name' => 'Motor', 'emission_factor_per_km' => 0.103],
            ['name' => 'Bus Umum', 'emission_factor_per_km' => 0.105],
            ['name' => 'Kereta Listrik (KRL)', 'emission_factor_per_km' => 0.041],
        ]);
    }
}
