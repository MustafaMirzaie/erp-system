<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'title' => $this->faker->words(2, true),
            'province' => $this->faker->state(),
            'city' => $this->faker->city(),
            'full_address' => $this->faker->address(),
            'is_default' => false,
        ];
    }

    public function default()
    {
        return $this->state([
            'is_default' => true,
        ]);
    }
}
