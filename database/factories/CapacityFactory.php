<?php
namespace Database\Factories;

use App\Models\Capacity;
use Illuminate\Database\Eloquent\Factories\Factory;

class CapacityFactory extends Factory
{
    protected $model = Capacity::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['64GB', '128GB', '256GB', '512GB']),
        ];
    }
}
