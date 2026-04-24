<?php

namespace Database\Factories;

use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'address_id' => CustomerAddress::factory(),
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('0##########'),
            'mobile' => $this->faker->numerify('09#########'),
        ];
    }
}
