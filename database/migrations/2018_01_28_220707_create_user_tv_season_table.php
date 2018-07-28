<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTvSeasonTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_tv_season', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('tv_season_id')->unsigned();
            $table->integer('list_status_id')->unsigned();
            $table->date('completed_at')->nullable();
            $table->integer('progress')->default(0);
            $table->smallInteger('score')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tv_season_id')->references('id')->on('tv_seasons')->onDelete('cascade');
            $table->foreign('list_status_id')->references('id')->on('list_statuses')->onDelete('cascade');

            $table->unique(['user_id', 'tv_season_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tv_season');
    }
}
