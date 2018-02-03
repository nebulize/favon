<?php

namespace App\Console\Commands;

use App\Services\IMDBScraper;
use App\Services\TVAPIService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;

class ScrapeIMDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:scrape {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape IMDB ids from an IMDB list of movies / tv-shows';

    /**
     * @var IMDBScraper
     */
    protected $imdbScraper;

    /**
     * ScrapeIMDB constructor.
     * @param IMDBScraper $scraper
     */
    public function __construct(IMDBScraper $scraper)
    {
        $this->imdbScraper = $scraper;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ids = $this->imdbScraper->scrape($this->argument('url'));
        $this->info('Scraping IMDB IDs...');
        $this->line(json_encode($ids));
    }
}
