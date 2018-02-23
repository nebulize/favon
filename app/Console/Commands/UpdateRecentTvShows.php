<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTvShowPopularity;
use App\Repositories\TvShowRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateRecentTvShows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:tv:update-recent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * UpdateRecentTvShows constructor.
     * @param TvShowRepository $tvShowRepository
     */
    public function __construct(TvShowRepository $tvShowRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
        parent::__construct();
    }

    /**
     * Dispatch a job to fetch updated popularity for each tv show created within the last 10 days.
     */
    public function handle(): void
    {
        // Fetch tv shows added in the last 10 days:
        $tvShows = $this->tvShowRepository->index([
            'created_at_gt' => Carbon::now()->subDays(10)
        ]);
        foreach ($tvShows as $tvShow) {
            UpdateTvShowPopularity::dispatch($tvShow->tmdb_id);
        }
    }
}
