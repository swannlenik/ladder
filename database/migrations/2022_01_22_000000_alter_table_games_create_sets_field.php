<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableGamesCreateSetsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->tinyInteger('sets')->default(1);
        });
        Schema::table('doubles', function (Blueprint $table) {
            $table->tinyInteger('sets')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('sets');
        });
        Schema::table('doubles', function (Blueprint $table) {
            $table->dropColumn('sets');
        });
    }
}
