<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponsableIdColumnToWebinarEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('webinar_events', function (Blueprint $table) {
            $table->foreignId('responsable_id')->nullable()->constrained('users')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webinar_events', function (Blueprint $table) {
            $table->dropForeign(['responsable_id']);
        });
    }
}
