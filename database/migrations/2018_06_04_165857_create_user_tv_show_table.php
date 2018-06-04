<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTvShowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tv_show', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('tv_show_id')->unsigned();
            $table->enum('status', ['watching', 'ptw', 'completed', 'dropped', 'hold']);
            $table->date('completed_at')->nullable();
            $table->float('score')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->unique(['user_id', 'tv_show_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tv_show');
    }
}
