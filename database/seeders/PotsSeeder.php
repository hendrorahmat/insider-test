<?php

namespace Database\Seeders;

use App\Models\Pots;
use Illuminate\Database\Seeder;

class PotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 4; $i++) {
            Pots::create([
                'name' => 'Pots ' . $i
            ]);
        }
    }
}
