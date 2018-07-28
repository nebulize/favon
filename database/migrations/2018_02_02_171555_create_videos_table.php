<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tv_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('key');
            $table->string('type')->nullable();
            $table->integer('tv_season_id')->unsigned();
            $table->timestamps();

            $table->foreign('tv_season_id')->references('id')->on('tv_seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_videos');
    }
}
