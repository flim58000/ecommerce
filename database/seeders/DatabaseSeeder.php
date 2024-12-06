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
        $this->call([
            ColorSeeder::class,
            CapacitySeeder::class,
            StockStatusSeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            CustomerAndRedemptionCodeSeeder::class,

        ]);
    }
}
