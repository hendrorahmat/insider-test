<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(['male']),
            'jersey_number' => $this->faker->numberBetween(1, 99),
            'strength' => $this->faker->numberBetween(50, 100),
        ];
    }
}
