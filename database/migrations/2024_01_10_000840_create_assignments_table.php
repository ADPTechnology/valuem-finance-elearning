<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('description', 5000)->nullable();
            $table->integer('value');
            $table->date('date_limit');
            $table->boolean('flg_groupal')->default(false);
            $table->boolean('flg_evaluable')->default(false);
            $table->char('active', 1);
            $table->foreignId('event_id')->constrained('events')->nullable();
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
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
        });
        Schema::dropIfExists('assignments');
    }
}
