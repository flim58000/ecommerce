<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' =>  'iPhone 15 Pro Max',
            'description' => $this->faker->sentence,
        ];
    }
}
