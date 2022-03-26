<?php

namespace Database\Factories;

use App\Models\Nation;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeagueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'total_points' => $this->faker->numberBetween(100, 1000),
            'nation_id' => Nation::factory()->make()->id
        ];
    }
}
