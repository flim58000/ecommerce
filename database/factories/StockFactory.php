<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\ProductVariant;
use App\Models\StockStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition()
    {
        return [
            'variant_id' => \App\Models\ProductVariant::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'status_id' => 1,

            
        ];
    }
}
