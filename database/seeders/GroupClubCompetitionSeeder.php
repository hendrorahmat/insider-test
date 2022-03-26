<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupClubCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clubPergroup = 4;
        $groups = Group::all();

        foreach ($groups as $group) {

        }
    }
}
