<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFCurveVideoQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_curve_video_questions', function (Blueprint $table) {
            $table->id();
            $table->string('statement', 500);
            $table->string('correct_answer', 500);
            $table->integer('points');
            $table->foreignId('f_curve_video_id')->constrained('f_curve_videos')->nullable();
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
        Schema::dropIfExists('f_curve_video_questions');
    }
}
