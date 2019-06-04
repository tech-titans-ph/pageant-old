<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryContestantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_contestants', function (Blueprint $table) {
			$table->bigIncrements('id');
			
			$table->unsignedBigInteger('contest_category_id');
			$table->foreign('contest_category_id')->references('id')->on('contest_categories');

			$table->unsignedBigInteger('contestant_id');
			$table->foreign('contestant_id')->references('id')->on('contestants');

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
        Schema::dropIfExists('category_contestants');
    }
}
