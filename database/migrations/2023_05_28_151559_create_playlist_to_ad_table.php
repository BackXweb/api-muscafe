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
        Schema::create('playlist_to_ad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id');
            $table->foreign('ad_id')->references('id')->on('ads');
            $table->unsignedBigInteger('playlist_id');
            $table->foreign('playlist_id')->references('id')->on('playlists');
            $table->dateTime('datetime');
            $table->boolean('use_any');
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
        Schema::dropIfExists('playlist_to_ad');
    }
};
