<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tv_shows', function (Blueprint $table) {
            $table->decimal('imdb_score', 9, 2)->default(0)->nullable(false)->change();
            $table->bigInteger('imdb_votes')->unsigned()->default(0)->nullable(false)->change();
            $table->index('imdb_id');
            $table->index('imdb_votes');
            $table->index('popularity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tv_shows', function (Blueprint $table) {
            $table->dropIndex(['imdb_id']);
        });
    }
}
