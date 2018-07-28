<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTvSeasonTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('person_tv_season', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id')->unsigned();
            $table->integer('tv_season_id')->unsigned();
            $table->enum('role', ['cast', 'crew']);
            $table->string('character')->nullable();
            $table->string('job')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('tv_season_id')->references('id')->on('tv_seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_tv_season');
    }
}
