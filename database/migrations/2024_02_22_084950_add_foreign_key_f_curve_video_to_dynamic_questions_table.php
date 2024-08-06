<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyFCurveVideoToDynamicQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dynamic_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('f_curve_video_id')->nullable()->after('exam_id');
            $table->foreign('f_curve_video_id')->references('id')->on('f_curve_videos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dynamic_questions', function (Blueprint $table) {
            $table->dropForeign(['f_curve_video_id']);
            $table->dropColumn('f_curve_video_id');
        });
    }
}
