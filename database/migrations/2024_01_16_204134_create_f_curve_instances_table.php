<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFCurveInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_curve_instances', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('days_count');
            $table->foreignId('forgetting_curve_id')->constrained('forgetting_curves')->nullable();
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
        Schema::dropIfExists('f_curve_instances');
    }
}
