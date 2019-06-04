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
            
            $table->unsignedTinyInteger('score');

            $table->unsignedBigInteger('contest_category_id');
            $table->foreign('contest_category_id')->references('id')->on('contest_categories');
            
            $table->unsignedBigInteger('category_contestant_id');
            $table->foreign('category_contestant_id')->references('id')->on('category_contestants');
            
            $table->unsignedBigInteger('category_judge_id');
            $table->foreign('category_judge_id')->references('id')->on('category_judges');

            $table->unsignedBigInteger('category_criteria_id');
            $table->foreign('category_criteria_id')->references('id')->on('category_criterias');

            $table->timestamps();
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
