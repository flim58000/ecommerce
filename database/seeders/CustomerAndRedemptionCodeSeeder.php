<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\RedemptionCode;
use Illuminate\Support\Facades\DB;

class CustomerAndRedemptionCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Customer::factory()->count(1)->create();

         RedemptionCode::factory()->create();
    }
}