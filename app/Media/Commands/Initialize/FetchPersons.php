<?php

namespace Favon\Media\Commands\Initialize;

use Illuminate\Console\Command;
use Favon\Media\Jobs\FetchPerson;
use Favon\Media\Services\FetchingService;

class FetchPersons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:fetch:persons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all persons from the TMDB API and store them in database (~100h!)';

    /**
     * @var FetchingService
     */
    protected $fetchingService;

    /**
     * FetchPersons constructor.
     * @param FetchingService $fetchingService
     */
    public function __construct(FetchingService $fetchingService)
    {
        parent::__construct();
        $this->fetchingService = $fetchingService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('Fetching latest person export from TMDB...');
        $this->fetchingService->fetchFile('person');
        $this->line('Extracting...');
        $this->fetchingService->extract('person');
        $this->info('Successfully extracted the person list.');
        $this->line('Queueing fetching jobs...');
        $this->fetchingService->fetch('person', FetchPerson::class);
        $this->fetchingService->deleteFiles('person');
        $this->info('Deleted downloaded files. The fetching of persons is queued and will take around 100 hours to complete');
    }
}
