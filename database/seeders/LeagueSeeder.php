<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Nation;
use Faker\Factory;
use Illuminate\Database\Seeder;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leagues = [
            'Premier League',
            'Bundesliga',
            'La-liga',
            'Serie A',
            'Ligue 1',
            'Primeira Liga',
            'Eredivisie',
            'Danish Superliga',
            'Champion League'
        ];

        Nation::factory()->create();

        foreach ($leagues as $index => $league) {
            League::create([
                'name' => $league,
                'ranking_points' => Factory::create()->numberBetween(100, 1000),
                'nation_id' => $index + 1
            ]);
        }
    }
}
