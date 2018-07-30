<?php

namespace Seeds\Media;

use Illuminate\Database\Seeder;

class ListStatusesSeeder extends Seeder
{
    protected const LIST_STATUSES = [
        [
            'name' => 'Watching',
            'slug' => 'watching',
        ],
        [
            'name' => 'Plan To Watch',
            'slug' => 'ptw',
        ],
        [
            'name' => 'Completed',
            'slug' => 'completed',
        ],
        [
            'name' => 'Dropped',
            'slug' => 'dropped',
        ],
        [
            'name' => 'On Hold',
            'slug' => 'hold',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::LIST_STATUSES as $status) {
            \Favon\Media\Models\ListStatus::create($status);
        }
    }
}
