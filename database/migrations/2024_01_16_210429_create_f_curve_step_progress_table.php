<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFCurveStepProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_curve_step_progress', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->double('score', 8, 2)->nullable();
            $table->foreignId('certification_id')->constrained('certifications')->nullable();
            $table->foreignId('f_curve_step_id')->constrained('f_curve_steps')->nullable();
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
        Schema::dropIfExists('f_curve_step_progress');
    }
}
