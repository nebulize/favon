<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tv_seasons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('number');
            $table->string('name')->nullable();
            $table->date('first_aired')->nullable();
            $table->text('summary')->nullable();
            $table->string('poster')->nullable();
            $table->integer('episode_count')->default(0);
            $table->float('rating')->nullable();
            $table->bigInteger('members_count')->default(0);
            $table->bigInteger('tmdb_id')->unsigned();
            $table->integer('tv_show_id')->unsigned();
            $table->integer('season_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');

            $table->unique(['number', 'tv_show_id']);
            $table->index('season_id');
            $table->index('number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_seasons');
    }
}
