<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalClub = Club::count();

        Player::factory()->count($totalClub * 11)->create();
    }
}
