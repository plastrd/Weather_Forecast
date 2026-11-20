<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       Location::updateOrCreate(
        ['lat' =>  41.1528, 'lon' => 24.1473],        
        ['name' => 'Drama', 'region' => 'East Macedonia and Thrace', 'country' => 'Greece', 'continent' => 'Europe', 'timezone' => 'Europe/Athens', 'altitude_m' => 111]
       );
    }
}
