<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\League;
use App\Models\LeagueClub;
use Faker\Factory;
use Illuminate\Database\Seeder;

class LeagueClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalClub = 20;
        $leagues = League::all();
        $currentPage = 1;
        $totalMatchPerClub = 38;

        foreach ($leagues as $league) {
            $clubs = Club::offset(($currentPage - 1) * $totalClub)->limit($totalClub)->get();
            foreach ($clubs as $club) {
                $totalWin = random_int(1, $totalMatchPerClub - 3);

                $restOfMatch = $totalMatchPerClub - $totalWin;
                $totalLoose = $restOfMatch - (round($restOfMatch / 2));
                $totalDraw = 0;
                if (($totalWin + $totalLoose) < $totalMatchPerClub) {
                    $totalDraw = $totalMatchPerClub - ($totalWin + $totalLoose);
                }

                $totalGoalFor = Factory::create()->numberBetween($totalWin * 2, 100);
                $totalGoalAgainst = Factory::create()->numberBetween(1, 100 - $totalWin);
                $totalGoalDifference = $totalGoalFor - $totalGoalAgainst;

                LeagueClub::create([
                    'club_id' => $club->id,
                    'league_id' => $league->id,
                    'win' => $totalWin,
                    'draw' => $totalDraw,
                    'loose' => $totalLoose,
                    'session_id' => 1,
                    'total_points' => ($totalWin * 3) + $totalDraw,
                    'goal_for' => $totalGoalFor,
                    'goal_against' => $totalGoalAgainst,
                    'goal_difference' => $totalGoalDifference
                ]);
            }
            $currentPage++;
        }
    }
}
