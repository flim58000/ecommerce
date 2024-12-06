<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run()
    {
        DB::table('colors')->insert([
            ['name' => 'Black', 'code' => '#000000'],
            ['name' => 'White', 'code' => '#c9c9c9'],
            ['name' => 'Gold', 'code' => '#FFD700'],
            ['name' => 'Silver', 'code' => '#a39898'],
        ]);
    }
}