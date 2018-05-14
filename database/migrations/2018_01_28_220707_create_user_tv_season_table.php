<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTvSeasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tv_season', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('tv_season_id')->unsigned();
            $table->enum('status', ['watching', 'ptw', 'completed', 'dropped', 'hold']);
            $table->date('completed_at')->nullable();
            $table->integer('progress')->default(0);
            $table->smallInteger('score')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tv_season_id')->references('id')->on('tv_seasons')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'tv_season_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tv_season');
    }
}
