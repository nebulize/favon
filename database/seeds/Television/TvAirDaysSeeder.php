<?php

namespace Seeds\Television;

use Illuminate\Database\Seeder;

class TvAirDaysSeeder extends Seeder
{
    protected const AIR_DAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
        'Daily',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::AIR_DAYS as $airDay) {
            \Favon\Television\Models\AirDay::create([
                'name' => $airDay
            ]);
        }
    }
}
