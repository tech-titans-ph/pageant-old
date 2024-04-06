<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepColumnToCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->unsignedDecimal('step')->nullable()->after('max_points_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->dropColumn(['step']);
        });
    }
}
