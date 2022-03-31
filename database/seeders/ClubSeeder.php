<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\League;
use App\Models\Nation;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = config('league.champion_league');

        $totalClubPerLeague = 20;
        $totalCountry = Nation::count();
        $lastIndexChampionLeague = 0;

        for ($i = 1; $i <= $totalCountry; $i++) {
            $totalClubChampionLeague = rand(2, 3);

            for ($c = 1; $c <= $totalClubChampionLeague; $c++) {
                if ($lastIndexChampionLeague < (count($data) - 1)) {
                    Club::factory()->create([
                        'name' => $data[$lastIndexChampionLeague],
                        'nation_id' => $i
                    ]);
                    ++$lastIndexChampionLeague;
                }
            }

            for ($j = 1; $j <= $totalClubPerLeague - $totalClubChampionLeague; $j++) {
                Club::factory()->create([
                    'nation_id' => $i
                ]);
            }
        }
    }
}
