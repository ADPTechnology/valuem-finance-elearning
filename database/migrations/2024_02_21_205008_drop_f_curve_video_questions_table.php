<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFCurveVideoQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('f_curve_video_questions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('f_curve_video_questions', function (Blueprint $table) {
            $table->id();
            $table->string('statement', 500);
            $table->string('correct_answer', 500);
            $table->integer('points');
            $table->foreignId('f_curve_video_id')->constrained('f_curve_videos')->nullable();
            $table->timestamps();
        });
    }
}
