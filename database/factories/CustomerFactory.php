<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'economic_code' => $this->faker->numerify('##########'),
            'national_id' => $this->faker->numerify('##########'),
            'credit_limit' => $this->faker->randomFloat(2, 0, 100000000),
            'status' => 'active',
        ];
    }

    public function withFullRelations()
    {
        return $this
            ->hasAddresses(2)
            ->has(
                \App\Models\CustomerAddress::factory()
                    ->hasContacts(2)
                    ->count(2),
                'addresses'
            );
    }
}
