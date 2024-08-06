<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFCurveVideoProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_curve_video_progress', function (Blueprint $table) {
            $table->id();
            $table->string('answer', 500)->nullable();
            $table->boolean('is_correct');
            $table->foreignId('f_curve_video_question_id')->constrained('f_curve_video_questions')->nullable();
            $table->foreignId('f_curve_step_progress_id')->constrained('f_curve_step_progress')->nullable();
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
        Schema::dropIfExists('f_curve_video_progress');
    }
}
