<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestCategoryContestantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_category_contestants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contest_category_id');
            $table->unsignedBigInteger('contestant_id');
            $table->unsignedTinyInteger('order');
            $table->enum('status', ['que', 'scoring', 'done'])->default('que');
            $table->timestamps();

            $table->foreign('contest_category_id')->references('id')->on('contest_categories');
            $table->foreign('contestant_id')->references('id')->on('contestants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_category_contestants');
    }
}
