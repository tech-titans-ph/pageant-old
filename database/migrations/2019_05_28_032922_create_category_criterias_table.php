<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_criterias', function (Blueprint $table) {
            $table->bigIncrements('id');

			$table->unsignedTinyInteger('percentage');

            $table->unsignedBigInteger('contest_category_id');
            $table->foreign('contest_category_id')->references('id')->on('contest_categories');

            $table->unsignedBigInteger('criteria_id');
            $table->foreign('criteria_id')->references('id')->on('criterias');

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
        Schema::dropIfExists('category_criterias');
    }
}
