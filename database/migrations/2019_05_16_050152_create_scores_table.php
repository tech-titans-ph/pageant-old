<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contest_category_id');
            $table->unsignedBigInteger('contest_category_contestant_id');
            $table->unsignedBigInteger('contest_category_criteria_id');
            $table->unsignedBigInteger('contest_category_judge_id');
            $table->unsignedTinyInteger('percentage');
            $table->timestamps();

            $table->foreign('contest_category_id')->references('id')->on('contest_categories');
            $table->foreign('contest_category_contestant_id')->references('id')->on('contest_category_contestants');
            $table->foreign('contest_category_criteria_id')->references('id')->on('contest_category_criterias');
            $table->foreign('contest_category_judge_id')->references('id')->on('contest_category_judges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
