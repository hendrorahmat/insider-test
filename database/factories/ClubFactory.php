<?php

namespace Database\Factories;

use App\Models\Nation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text
        ];
    }
}
