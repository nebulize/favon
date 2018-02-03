<?php

namespace App\Console\Commands;

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

    protected $tmdbService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $changes = $this->tmdbService->getChangedPersons($this->argument('start'), $this->argument('end'));
        foreach ($changes as $change) {

        }
    }
}
