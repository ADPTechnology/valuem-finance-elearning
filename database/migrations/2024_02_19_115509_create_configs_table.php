<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('whatsapp_number')->default(51944568496);
            $table->string('whatsapp_message');
            $table->string('email');
            $table->string('facebook_link')->default('https://www.facebook.com/HamaPeruOficial/');
            $table->string('linkedin_link')->default('https://www.linkedin.com/company/hama-perú/');
            $table->string('instagram_link')->default('https://www.instagram.com/hamaperu/');
            $table->timestamps();
        });

        DB::table('configs')->insert([
            'whatsapp_message' => 'Hola, Necesito información sobre Hama Perú',
            'email' => 'hamaperu@gmail.com',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
