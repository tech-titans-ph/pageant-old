<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestCategoryCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_category_criterias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contest_category_id');
            $table->unsignedBigInteger('criteria_id');
            $table->unsignedTinyInteger('percentage');
            $table->timestamps();

            $table->foreign('contest_category_id')->references('id')->on('contest_categories');
            $table->foreign('criteria_id')->references('id')->on('criterias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_category_criterias');
    }
}
