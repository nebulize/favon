<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_shows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imdb_id', 9)->unique();
            $table->string('name');
            $table->enum('status', ['Continuing', 'Planned', 'In Production', 'Ended', 'Canceled', 'Pilot']);
            $table->date('first_aired');
            $table->string('network')->nullable();
            $table->string('runtime')->nullable();
            $table->enum('rating', ['TV-MA', 'TV-14', 'TV-PG', 'TV-G', 'TV-Y', 'TV-Y7'])->nullable();
            $table->text('summary')->nullable();
            $table->text('plot')->nullable();
            $table->string('country')->nullable();
            $table->string('poster')->nullable();
            $table->string('banner')->nullable();
            $table->float('imdb_score')->nullable();
            $table->bigInteger('imdb_votes')->unsigned()->nullable();
            $table->enum('air_day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Daily'])->nullable();
            $table->string('air_time')->nullable();
            $table->bigInteger('tvdb_id')->unsigned()->nullable();
            $table->bigInteger('tmdb_id')->unsigned()->nullable();
            $table->string('homepage')->nullable();
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
        Schema::dropIfExists('tv_shows');
    }
}
