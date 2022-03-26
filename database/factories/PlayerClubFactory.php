<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerClubFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'player_id' => Player::factory(),
            'club_id' => random_int(1, 15)
        ];
    }
}
