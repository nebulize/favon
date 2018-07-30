<?php

namespace Seeds\Television;

use Illuminate\Database\Seeder;

class TvRatingsSeeder extends Seeder
{
    protected const RATINGS = [
        'TV-MA',
        'TV-14',
        'TV-PG',
        'TV-G',
        'TV-Y',
        'TV-Y7'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::RATINGS as $rating) {
            \Favon\Television\Models\Rating::create([
                'name' => $rating,
            ]);
        }
    }
}
