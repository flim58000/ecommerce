<?php

 
namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'color_id' => Color::inRandomOrder()->first()->id, 
            'capacity_id' => Capacity::inRandomOrder()->first()->id, 
            'price' => $this->faker->randomFloat(2, 1000, 3000),
            'sku' => $this->faker->unique()->lexify('SKU-?????'),
        ];
    }
}