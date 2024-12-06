<?php

namespace Database\Factories;

 
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'balance' => 30000, // กำหนดยอดเงินสุ่มระหว่าง 1000-10000
        ];
    }
}

