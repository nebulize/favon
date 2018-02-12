<?php

namespace App\Console\Commands;

use App\Jobs\UpdatePerson;
use App\Services\TMDBService;
use Illuminate\Console\Command;

class UpdatePersons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:persons:update {start?} {end?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store information for all persons changed in the last 24 hours (or time period - max 14 days - defined by start and end date)';

    /**
     * @var TMDBService
     */
    protected $tmdbService;

    /**
     * UpdatePersons constructor.
     *
     * @param TMDBService $tmdbService
     */
    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle() : void
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
    public function displayStatus($start, $end) : void
    {
        $line = 'Fetching changed persons from TMDB ';
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
     * @return null
     */
    public function fetch(int $page = 1)
    {
        $changes = $this->requestPersonChanges($page);
        if ($changes === null) {
            return;
        }
        $this->line('Fetched '.$page.'/'.$changes->total_pages.' pages of updated persons. Dispatching jobs...');
        $this->updateBatch($changes->results);
        if ($changes->page < $changes->total_pages) {
            return $this->fetch($page + 1);
        }
    }

    /**
     * Request changes persons, paginated.
     *
     * @param $page
     * @return null|\stdClass
     */
    public function requestPersonChanges($page) : ?\stdClass
    {
        return $this->tmdbService->getChangedPersons($this->argument('start'), $this->argument('end'), $page);
    }

    /**
     * Update an array of persons.
     *
     * @param array $persons
     */
    public function updateBatch(array $persons) : void
    {
        foreach ($persons as $person) {
            if (\is_object($person) && property_exists($person, 'id')) {
                UpdatePerson::dispatch($person->id);
            }
        }
    }
}
