<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('stock_statuses')->insert([
            ['name' => 'IN-STOCK'],
            ['name' => 'RESERVED'],
            ['name' => 'SOLD'],
        ]);
    }
}