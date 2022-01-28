<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoublePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('double_players', function (Blueprint $table) {
            $table->integer('groupId');
            $table->integer('player1');
            $table->integer('player2');
            $table->integer('player3');
            $table->integer('player4');
            $table->integer('player5')->default(0);
            $table->primary(['groupId', 'player1', 'player2', 'player3', 'player4', 'player5']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('double_players');
    }
}
