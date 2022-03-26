<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\League;
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
        $data = [
            'Manchester City',
            'Manchester United',
            'Liverpool',
            'Chelsea',
            'Atlético',
            'Real Madrid',
            'Barcelona',
            'Sevilla',
            'Villarreal',
            'Inter Milan',
            'AC Milan',
            'Atalanta',
            'Juventus',
            'Bayern',
            'Leipzig',
            'Dortmund',
            'Wolfsburg',
            'LOSC',
            'Paris',
            'Sporting CP',
            'Porto',
            'Benfica',
            'Ajax',
            'Zenit',
            'Salzburg',
            'Club Brugge',
            'Dynamo Kyiv',
            'Shakhtar Donetsk',
            'Beşiktaş',
            'Young Boys',
            'Malmö',
            'Sheriff'
        ];

        foreach ($data as $d) {
            Club::create([
                'name' => $d,
                'description' => Factory::create()->text,
            ]);
        }
        $totalLeague = League::count();
        $totalClubPerLeague = 20;

        Club::factory()->count(($totalLeague * $totalClubPerLeague) - count($data))->create();
    }
}
