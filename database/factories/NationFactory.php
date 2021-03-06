<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country,
            'code' => $this->faker->countryCode
        ];
    }
}
