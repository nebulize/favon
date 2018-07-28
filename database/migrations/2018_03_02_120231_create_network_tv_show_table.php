<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkTvShowTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('network_tv_show', function (Blueprint $table) {
            $table->integer('tv_show_id')->unsigned();
            $table->integer('network_id')->unsigned();

            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');

            $table->primary(['tv_show_id', 'network_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_tv_show');
    }
}
