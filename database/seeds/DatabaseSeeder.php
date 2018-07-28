<?php

use Illuminate\Database\Seeder;

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
