<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotsClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pots_clubs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Pots::class);
            $table->foreignIdFor(\App\Models\Club::class);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pots_id')->references('id')->on('pots');
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
        Schema::dropIfExists('pots_clubs');
    }
}
