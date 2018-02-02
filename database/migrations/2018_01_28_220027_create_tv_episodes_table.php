<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number')->default(0);
            $table->string('name')->nullable();
            $table->date('first_aired')->nullable();
            $table->text('plot')->nullable();
            $table->bigInteger('tmdb_id')->unsigned();
            $table->integer('tv_season_id')->unsigned();
            $table->foreign('tv_season_id')->references('id')->on('tv_seasons')->onDelete('cascade');
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
        Schema::dropIfExists('tv_episodes');
    }
}
