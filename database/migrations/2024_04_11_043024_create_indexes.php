<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexes extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('judges', function (Blueprint $table) {
            $table->index('contest_id');
        });

        Schema::table('contestants', function (Blueprint $table) {
            $table->index('contest_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('contest_id');
        });

        Schema::table('category_judges', function (Blueprint $table) {
            $table->index(['category_id', 'judge_id']);
        });

        Schema::table('category_contestants', function (Blueprint $table) {
            $table->index(['category_id', 'contestant_id']);
        });

        Schema::table('criterias', function (Blueprint $table) {
            $table->index('category_id');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('criteria_id');
            $table->index('category_contestant_id');
            $table->index('category_judge_id');
        });

        Schema::table('bestins', function (Blueprint $table) {
            $table->index('contest_id');
            $table->index('type');
            $table->index('type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('judges', function (Blueprint $table) {
            $table->dropIndex('judges_contest_id_index');
        });

        Schema::table('contestants', function (Blueprint $table) {
            $table->dropIndex('contestants_contest_id_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_contest_id_index');
        });

        Schema::table('category_judges', function (Blueprint $table) {
            $table->dropIndex('category_judges_category_id_judge_id_index');
        });

        Schema::table('category_contestants', function (Blueprint $table) {
            $table->dropIndex('category_contestants_category_id_contestant_id_index');
        });

        Schema::table('criterias', function (Blueprint $table) {
            $table->dropIndex('criterias_category_id_index');
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->dropIndex('scores_category_id_index');
            $table->dropIndex('scores_criteria_id_index');
            $table->dropIndex('scores_category_contestant_id_index');
            $table->dropIndex('scores_category_judge_id_index');
        });

        Schema::table('bestins', function (Blueprint $table) {
            $table->dropIndex('bestins_contest_id_index');
            $table->dropIndex('bestins_type_index');
            $table->dropIndex('bestins_type_id_index');
        });
    }
}
