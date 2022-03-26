<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Player;
use App\Models\PlayerClub;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayerClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $clubs = Club::all();
        $maxPlayer = 11;
        $currentPage = 1;

        foreach ($clubs as $club) {
            $players = Player::offset(($currentPage - 1) * $maxPlayer)->limit($maxPlayer)->get();
            $clubStrength = 0;
            foreach ($players as $player) {
                PlayerClub::create([
                    'player_id' => $player->id,
                    'club_id' => $club->id
                ]);
                $clubStrength += $player->strength;
            }
            $currentPage++;
            Club::findOrFail($club->id)->update([
                'strength' => $clubStrength
            ]);
        }

    }
}
