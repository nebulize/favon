<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTvShowTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('language_tv_show', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tv_show_id')->unsigned();
            $table->string('language_code', 2);

            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->foreign('language_code')->references('code')->on('languages')->onDelete('cascade');

            $table->unique(['tv_show_id', 'language_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_tv_show');
    }
}
