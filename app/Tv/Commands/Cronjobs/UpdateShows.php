<?php

namespace Favon\Tv\Commands\Cronjobs;

use Favon\Tv\Jobs\UpdateShow;
use Illuminate\Console\Command;
use Favon\Tv\Http\Clients\TmdbTvClient;

class UpdateShows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:update:shows {start?} {end?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store information for all tv shows changed in the last 24 hours (or time period - max 14 days - defined by start and end date)';

    /**
     * @var TmdbTvClient
     */
    protected $tmdbClient;

    /**
     * UpdateShows constructor.
     * @param TmdbTvClient $tmdbClient
     */
    public function __construct(TmdbTvClient $tmdbClient)
    {
        $this->tmdbClient = $tmdbClient;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $start = $this->argument('start');
        $end = $this->argument('end');
        $this->displayStatus($start, $end);
        $this->fetch();
        $this->info("Successfully queued all jobs. Start the queue worker if it's not working to start processing them.");
    }

    /**
     * Display initial command line status.
     *
     * @param string $start
     * @param string $end
     */
    public function displayStatus($start, $end): void
    {
        $line = 'Fetching changed tv shows from TMDB ';
        if ($start && $start !== '') {
            if ($end && $end !== '') {
                $line .= 'between '.$start.' and '.$end;
            } else {
                $line .= 'since '.$start;
            }
        } else {
            $line .= 'for the last 24 hours';
        }
        $line .= '...';
        $this->line($line);
    }

    /**
     * Recursive function to fetch all pages and dispatch jobs.
     *
     * @param int $page
     */
    public function fetch(int $page = 1): void
    {
        $changedTvShowsResponse = $this->tmdbClient->getChangedTvShows($page, $this->argument('start'), $this->argument('end'));
        if ($changedTvShowsResponse->hasBeenSuccessful() === false) {
            return;
        }
        $this->line('Fetched '.$page.'/'.$changedTvShowsResponse->getTotalPages().' pages of updated tv shows. Dispatching jobs...');
        $this->updateBatch($changedTvShowsResponse->getResults());
        if ($changedTvShowsResponse->getPage() < $changedTvShowsResponse->getTotalPages()) {
            $this->fetch($page + 1);
        }
    }

    /**
     * Update an array of tv shows.
     *
     * @param array $tvShows
     */
    public function updateBatch(array $tvShows): void
    {
        foreach ($tvShows as $tvShow) {
            if (\is_object($tvShow) && property_exists($tvShow, 'id') && $tvShow->id !== null) {
                UpdateShow::dispatch($tvShow->id);
            }
        }
    }
}
