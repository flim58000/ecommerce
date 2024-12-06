<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColorFactory extends Factory
{
    protected $model = Color::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Black', 'White', 'Gold', 'Silver']),
            'code' => $this->faker->hexColor,
            
        ];
    }
}