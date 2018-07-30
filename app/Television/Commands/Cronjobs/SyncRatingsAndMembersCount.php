<?php

namespace Favon\Television\Commands\Cronjobs;

use Favon\Television\Repositories\TvSeasonRepository;
use Illuminate\Console\Command;

class SyncRatingsAndMembersCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:update:tv:ratings';

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
