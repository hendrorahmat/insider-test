<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\League::class);
            $table->foreignIdFor(\App\Models\Club::class);
            $table->foreignIdFor(\App\Models\Session::class);
            $table->integer('total_points')->default(0);
            $table->integer('match')->default(38);
            $table->integer('win')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('loose')->default(0);
            $table->integer('goal_for')->default(0);
            $table->integer('goal_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_clubs');
    }
}
