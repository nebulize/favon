<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tv_shows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('first_aired')->nullable();
            $table->string('runtime')->nullable();
            $table->text('summary')->nullable();
            $table->text('plot')->nullable();
            $table->string('poster')->nullable();
            $table->string('banner')->nullable();
            $table->string('homepage')->nullable();
            $table->integer('production_status_id')->unsigned()->nullable();
            $table->integer('tv_rating_id')->unsigned()->nullable();
            $table->integer('tv_air_day_id')->unsigned()->nullable();
            $table->string('air_time')->nullable();
            $table->float('popularity')->nullable();
            $table->decimal('imdb_score', 9, 2)->default(0);
            $table->bigInteger('imdb_votes')->unsigned()->default(0);
            $table->string('imdb_id', 9)->nullable();
            $table->bigInteger('tvdb_id')->unsigned()->nullable();
            $table->bigInteger('tmdb_id')->unsigned()->unique();
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();

            $table->foreign('production_status_id')->references('id')->on('production_statuses')->onDelete('cascade');
            $table->foreign('tv_rating_id')->references('id')->on('tv_ratings')->onDelete('cascade');
            $table->foreign('tv_air_day_id')->references('id')->on('tv_air_days')->onDelete('cascade');

            $table->index('imdb_id');
            $table->index('imdb_votes');
            $table->index('popularity');
            $table->index('is_hidden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_shows');
    }
}
