<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->date('birthday')->nullable();
            $table->date('deathday')->nullable();
            $table->string('name');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('biography')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('photo')->nullable();
            $table->bigInteger('tmdb_id')->unsigned()->unique();
            $table->timestamps();

            $table->index('tmdb_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
}
