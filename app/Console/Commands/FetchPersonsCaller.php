<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FetchPersonsCaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:persons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all persons from the TMDB API and store them in database (~100h!)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Fetching persons. This will take around 100 hours to complete');
        Artisan::queue('favon:persons-fetch');
    }
}
