<?php

namespace Favon\Television\Commands\Initialize;

use Favon\Media\Services\FetchingService;
use Illuminate\Console\Command;
use Favon\Television\Jobs\FetchShow;

class FetchShows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:fetch:shows';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all TV shows from TMDB and store them in database';

    /**
     * @var FetchingService
     */
    protected $fetchingService;

    /**
     * FetchShows constructor.
     * @param FetchingService $fetchingService
     */
    public function __construct(FetchingService $fetchingService)
    {
        parent::__construct();
        $this->fetchingService = $fetchingService;
    }

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        $this->line('Fetching latest tv shows export from TMDB...');
        $this->fetchingService->fetchFile('tv_series');
        $this->line('Extracting...');
        $this->fetchingService->extract('tv_series');
        $this->info('Successfully extracted the tv show list.');
        $this->line('Queueing fetching jobs...');
        $this->fetchingService->fetch('tv_series', FetchShow::class);
        $this->fetchingService->deleteFiles('tv_series');
        $this->info('Deleted downloaded files. The fetching of tv shows is queued and will take around 80 hours to complete');
    }
}
