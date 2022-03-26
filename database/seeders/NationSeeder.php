<?php

namespace Database\Seeders;

use App\Models\Nation;
use Illuminate\Database\Seeder;

class NationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Nation::factory()->count(8)->create();
    }
}
