<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFcEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_fc_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fc_evaluation_id')->constrained('freecourse_evaluations');
            $table->foreignId('p_certification_id')->constrained('product_certifications');
            $table->string('notes', 5000)->nullable();
            $table->double('points', 8, 2)->nullable();
            $table->string('status');
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
        Schema::dropIfExists('user_fc_evaluations');
    }
}
