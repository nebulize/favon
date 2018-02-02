<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->date('first_aired');
            $table->text('summary')->nullable();
            $table->string('poster')->nullable();
            $table->bigInteger('tmdb_id')->unsigned();
            $table->integer('tv_show_id')->unsigned();
            $table->integer('season_id')->unsigned();
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
            $table->unique(['number', 'tv_show_id']);
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
        Schema::dropIfExists('tv_seasons');
    }
}
