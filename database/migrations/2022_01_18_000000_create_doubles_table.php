<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoublesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doubles', function (Blueprint $table) {
            $table->id();
            $table->integer('groupId');
            $table->integer('opponent1');
            $table->integer('opponent2');
            $table->integer('opponent3');
            $table->integer('opponent4');
            $table->integer('score1')->default(0);
            $table->integer('score2')->default(0);
            $table->unique(['groupId', 'opponent1', 'opponent2', 'opponent3', 'opponent4']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doubles');
    }
}
