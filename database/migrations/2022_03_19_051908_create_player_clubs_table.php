<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Player::class);
            $table->foreignIdFor(\App\Models\Club::class);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('club_id')->references('id')->on('clubs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_clubs');
    }
}
