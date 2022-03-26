<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SessionSeeder::class,
            NationSeeder::class,
            PotsSeeder::class,
            LeagueSeeder::class,
            ClubSeeder::class,
            PlayerSeeder::class,
            PlayerClubSeeder::class,
            GroupSeeder::class,
            LeagueClubSeeder::class,
        ]);
    }
}
