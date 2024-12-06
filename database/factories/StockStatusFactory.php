<?php

namespace Database\Factories;

use App\Models\StockStatus;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockStatus>
 */
class StockStatusFactory extends Factory
{
    protected $model = StockStatus::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['IN-STOCK', 'RESERVED', 'SOLD']),
        ];
    }
}
