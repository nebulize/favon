<?php

namespace Favon\Media\Commands\Cronjobs;

use Illuminate\Console\Command;
use Favon\Media\Jobs\UpdatePerson;
use Favon\Media\Http\Clients\TmdbMediaClient;

class UpdatePersons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:update:persons {start?} {end?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store information for all persons changed in the last 24 hours (or time period - max 14 days - defined by start and end date)';

    /**
     * @var TmdbMediaClient
     */
    protected $tmdbClient;

    /**
     * UpdatePersons constructor.
     * @param TmdbMediaClient $tmdbClient
     */
    public function __construct(TmdbMediaClient $tmdbClient)
    {
        $this->tmdbClient = $tmdbClient;
        parent::__construct();
    }

    /**
     * Execute the command.
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
     */
    public function fetch(int $page = 1): void
    {
        $changedPersonsResponse = $this->tmdbClient->getChangedPersons($page, $this->argument('start'), $this->argument('end'));
        if ($changedPersonsResponse->hasBeenSuccessful() === false) {
            return;
        }
        $this->line('Fetched '.$page.'/'.$changedPersonsResponse->getTotalPages().' pages of updated persons. Dispatching jobs...');
        $this->updateBatch($changedPersonsResponse->getResults());
        if ($changedPersonsResponse->getPage() < $changedPersonsResponse->getTotalPages()) {
            $this->fetch($page + 1);
        }
    }

    /**
     * Update an array of persons.
     *
     * @param array $persons
     */
    public function updateBatch(array $persons): void
    {
        foreach ($persons as $person) {
            if (\is_object($person) && property_exists($person, 'id')) {
                UpdatePerson::dispatch($person->id);
            }
        }
    }
}
