<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignables', function (Blueprint $table) {
            $table->id();
            $table->string('notes', 1000)->nullable();
            $table->double('points', 8, 2)->default(0)->nullable();
            $table->string('status', 20);
            $table->foreignId('assignment_id')->constrained('assignments')->nullable();
            $table->string('assignable_id');
            $table->string('assignable_type'); // grupo o individual
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
        Schema::dropIfExists('assignables');
    }
}
