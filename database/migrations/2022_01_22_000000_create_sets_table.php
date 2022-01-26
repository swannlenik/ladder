<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->integer('gameId');
            $table->tinyInteger('isSingle')->default(1);
            $table->integer('setsOrder')->default(1);
            $table->integer('score1')->default(0);
            $table->integer('score2')->default(0);
            $table->primary(['gameId', 'isSingle', 'setsOrder']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sets');
    }
}
