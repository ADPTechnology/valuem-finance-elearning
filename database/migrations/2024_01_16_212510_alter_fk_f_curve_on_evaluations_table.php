<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFkFCurveOnEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->foreignId('f_curve_step_progress_id')->nullable()->constrained('f_curve_step_progress');
            $table->unsignedBigInteger('certification_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['f_curve_step_progress_id']);
            $table->unsignedBigInteger('certification_id')->change();
        });
    }
}
