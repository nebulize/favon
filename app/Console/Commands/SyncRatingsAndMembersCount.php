<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\TvSeasonRepository;

class SyncRatingsAndMembersCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:tv:ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync average rating and members count for each tv season';

    /**
     * @param TvSeasonRepository $tvSeasonRepository
     */
    public function handle(TvSeasonRepository $tvSeasonRepository): void
    {
        $tvSeasonRepository->syncMembersCount();
        $tvSeasonRepository->syncRatings();
    }
}
