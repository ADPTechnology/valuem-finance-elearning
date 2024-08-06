<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFkFCurvesOnExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('f_curve_step_id')->nullable()->constrained('f_curve_steps');
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->unsignedBigInteger('owner_company_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['f_curve_step_id']);
            $table->unsignedBigInteger('course_id')->change();
            $table->unsignedBigInteger('owner_company_id')->change();
        });
    }
}
