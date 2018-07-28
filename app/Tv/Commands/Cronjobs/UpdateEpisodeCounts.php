<?php

namespace Favon\Tv\Commands\Cronjobs;

use Illuminate\Console\Command;
use Favon\Tv\Jobs\UpdateEpisodeCount;
use Favon\Tv\Repositories\TvSeasonRepository;

class UpdateEpisodeCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:update:tv:counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update episode counts for all tv seasons';

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * UpdateEpisodeCounts constructor.
     * @param TvSeasonRepository $tvSeasonRepository
     */
    public function __construct(TvSeasonRepository $tvSeasonRepository)
    {
        $this->tvSeasonRepository = $tvSeasonRepository;
        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        foreach ($this->tvSeasonRepository->cursor() as $tvSeason) {
            UpdateEpisodeCount::dispatch($tvSeason);
        }
    }
}
