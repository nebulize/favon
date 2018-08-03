<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('slug', 10)->nullable();
        });

        DB::table('seasons')->where('name', 'Winter')->update(['slug' => 'winter']);
        DB::table('seasons')->where('name', 'Spring')->update(['slug' => 'spring']);
        DB::table('seasons')->where('name', 'Summer')->update(['slug' => 'summer']);
        DB::table('seasons')->where('name', 'Fall')->update(['slug' => 'fall']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
