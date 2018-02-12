<?php

namespace App\Console\Commands;

use App\Repositories\LanguageRepository;
use App\Services\TMDBService;
use Illuminate\Console\Command;

class FetchLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:languages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all available languages from the TMDB API and stores them in database';

    /**
     * @var TMDBService
     */
    protected $tmdbService;

    /**
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * FetchLanguages constructor.
     *
     * @param TMDBService        $tmdbService
     * @param LanguageRepository $languageRepository
     */
    public function __construct(TMDBService $tmdbService, LanguageRepository $languageRepository)
    {
        $this->tmdbService = $tmdbService;
        $this->languageRepository = $languageRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return null
     */
    public function handle()
    {
        $this->line('Fetching languages from TMDB...');
        $languages = $this->tmdbService->getLanguages();
        if ($languages === null) {
            $this->error('Error fetching languages. Check logs.');
        }
        foreach ($languages as $language) {
            $this->languageRepository->create($language);
        }
        $this->info('Successfully fetched and stored languages.');
    }
}
