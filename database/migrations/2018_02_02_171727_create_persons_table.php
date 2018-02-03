<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->date('birthday')->nullable();
            $table->date('deathday')->nullable();
            $table->string('name');
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->text('biography')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('photo')->nullable();
            $table->bigInteger('tmdb_id')->unsigned();
            $table->timestamps();
            $table->index('tmdb_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
