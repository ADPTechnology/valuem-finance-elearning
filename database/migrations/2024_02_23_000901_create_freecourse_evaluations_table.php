<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreecourseEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freecourse_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_section_id')->constrained('course_sections');
            $table->foreignId('exam_id')->nullable()->constrained('exams');
            $table->string('title');
            $table->string('description', 5000)->nullable();
            $table->integer('value');
            $table->char('active', 1);
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
        Schema::dropIfExists('freecourse_evaluations');
    }
}
