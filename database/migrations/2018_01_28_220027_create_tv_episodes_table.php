<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tv_episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number')->default(0);
            $table->string('name')->nullable();
            $table->date('first_aired')->nullable();
            $table->text('plot')->nullable();
            $table->bigInteger('tmdb_id')->unsigned();
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
        Schema::dropIfExists('tv_episodes');
    }
}
