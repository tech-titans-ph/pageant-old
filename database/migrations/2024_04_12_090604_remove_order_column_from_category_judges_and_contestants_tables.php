<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOrderColumnFromCategoryJudgesAndContestantsTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('category_judges', function (Blueprint $table) {
            $table->dropColumn(['order']);
        });

        Schema::table('category_contestants', function (Blueprint $table) {
            $table->dropColumn(['order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('category_judges', function (Blueprint $table) {
            $table->unsignedTinyInteger('order')->default(0)->after('judge_id');
        });

        Schema::table('category_contestants', function (Blueprint $table) {
            $table->unsignedTinyInteger('order')->default(0)->after('contestant_id');
        });
    }
}
