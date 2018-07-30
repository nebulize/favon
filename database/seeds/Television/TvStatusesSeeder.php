<?php

namespace Seeds\Television;

use Illuminate\Database\Seeder;

class TvStatusesSeeder extends Seeder
{
    protected const STATUSES = [
        'Continuing',
        'Planned',
        'In Production',
        'Ended',
        'Canceled',
        'Pilot',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::STATUSES as $status) {
            \Favon\Television\Models\ProductionStatus::create([
                'name' => $status,
            ]);
        }
    }
}
