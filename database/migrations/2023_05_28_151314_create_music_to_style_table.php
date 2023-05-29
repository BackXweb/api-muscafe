<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_to_style', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('music_id');
            $table->foreign('music_id')->references('id')->on('musics');
            $table->unsignedBigInteger('style_id');
            $table->foreign('style_id')->references('id')->on('styles');
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
        Schema::dropIfExists('music_to_style');
    }
};
