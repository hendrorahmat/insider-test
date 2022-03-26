<?php

namespace Database\Seeders;

use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::create([
            'name' => '2020/2021',
            'starting_date' => Carbon::now()->subYear()->startOfYear(),
            'end_date' => Carbon::now()->subYear()->startOfYear(),
            'is_active' => true,
        ]);

        Session::create([
            'name' => '2021/2022',
            'starting_date' => Carbon::now()->startOfYear(),
            'end_date' => Carbon::now()->startOfYear(),
            'is_active' => false,
        ]);
    }
}
