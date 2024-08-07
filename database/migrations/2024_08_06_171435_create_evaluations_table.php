<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_time');
            $table->string('statement', 1000);
            $table->string('correct_alternatives', 500);
            $table->string('selected_alternatives', 500);
            $table->double('points', 8, 2);
            $table->boolean('is_correct');
            $table->foreignId('question_id')->constrained('dynamic_questions');
            $table->foreignId('user_fc_evaluation_id')->constrained('user_fc_evaluations');
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
        Schema::dropIfExists('evaluations');
    }
}
