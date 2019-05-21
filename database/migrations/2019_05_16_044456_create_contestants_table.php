<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contestants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('number');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('picture');
            $table->unsignedBigInteger('contest_id');
            $table->timestamps();

            $table->foreign('contest_id')->references('id')->on('contests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contestants');
    }
}
