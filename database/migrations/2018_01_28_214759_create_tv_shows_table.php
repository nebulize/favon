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
            $table->string('imdb_id', 9);
            $table->string('name');
            $table->enum('status', ['Continuing', 'Completed']);
            $table->date('first_aired');
            $table->string('network')->nullable();
            $table->integer('runtime')->nullable();
            $table->enum('rating', ['TV-MA', 'TV-14', 'TV-PG', 'TV-G'])->nullable();
            $table->string('director')->nullable();
            $table->string('writer')->nullable();
            $table->string('actors')->nullable();
            $table->text('summary')->nullable();
            $table->text('plot')->nullable();
            $table->string('country')->nullable();
            $table->string('poster')->nullable();
            $table->string('banner')->nullable();
            $table->float('imdb_score');
            $table->float('imdb_votes');
            $table->enum('air_day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])->nullable();
            $table->string('air_time')->nullable();
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
