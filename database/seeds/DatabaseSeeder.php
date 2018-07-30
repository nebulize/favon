<?php

namespace Seeds;

use Illuminate\Database\Seeder;
use Seeds\Media\ListStatusesSeeder;
use Seeds\Television\TvAirDaysSeeder;
use Seeds\Television\TvRatingsSeeder;
use Seeds\Television\TvStatusesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(TvStatusesSeeder::class);
        $this->call(TvRatingsSeeder::class);
        $this->call(TvAirDaysSeeder::class);
        $this->call(ListStatusesSeeder::class);
    }
}
