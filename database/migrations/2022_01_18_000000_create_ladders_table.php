<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaddersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ladders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('date')->default('CURRENT_TIMESTAMP');
            $table->tinyInteger('deletable')->default(0);
            $table->tinyInteger('isSingle')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ladders');
    }
}
