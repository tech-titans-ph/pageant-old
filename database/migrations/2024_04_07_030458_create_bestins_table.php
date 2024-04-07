<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBestinsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bestins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contest_id');

            $table->string('type');
            $table->unsignedBigInteger('type_id');
            $table->string('name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('bestins');
    }
}
