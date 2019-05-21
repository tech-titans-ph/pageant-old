<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestCategoryJudgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_category_judges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contest_category_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('contest_category_id')->references('id')->on('contest_categories');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_category_judges');
    }
}
