<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChanePointsFromIntegerToDecimalInScoresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->unsignedDecimal('points')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->unsignedTinyInteger('points')->default(0)->change();
        });
    }
}
