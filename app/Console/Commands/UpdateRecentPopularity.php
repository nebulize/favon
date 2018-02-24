<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTvShowPopularity;
use App\Repositories\SeasonRepository;
use App\Repositories\TvSeasonRepository;
use App\Repositories\TvShowRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class UpdateRecentPopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:tv:popularity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the popularity values of all tv shows from this and future seasons';

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * @var SeasonRepository
     */
    protected $seasonRepository;

    /**
     * UpdateRecentPopularity constructor.
     * @param TvSeasonRepository $tvSeasonRepository
     * @param TvShowRepository $tvShowRepository
     * @param SeasonRepository $seasonRepository
     */
    public function __construct(TvSeasonRepository $tvSeasonRepository, TvShowRepository $tvShowRepository,
                                SeasonRepository $seasonRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->seasonRepository = $seasonRepository;
        parent::__construct();
    }

    /**
     * Dispatch a job to fetch updated popularity for each tv show created within the last 10 days.
     */
    public function handle(): void
    {
        try {
            $currentSeason = $this->seasonRepository->find([
                'date' => Carbon::now()
            ]);
        } catch (ModelNotFoundException $e) {
            Log::warning('UpdateRecentPopularity: Could not find current season.');
        }

        $tvShows = $this->tvShowRepository->index([
            'season_gt' => $currentSeason
        ]);

        foreach ($tvShows as $tvShow) {
            UpdateTvShowPopularity::dispatch($tvShow->tmdb_id);
        }
    }
}
