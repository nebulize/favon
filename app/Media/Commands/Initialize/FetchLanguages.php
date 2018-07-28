<?php

namespace Favon\Media\Commands\Initialize;

use Illuminate\Console\Command;
use Favon\Media\Http\Clients\TmdbClient;
use Favon\Media\Repositories\LanguageRepository;

class FetchLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:fetch:languages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all available languages from the TMDB API and stores them in database';

    /**
     * Execute the command.
     *
     * @param TmdbClient $tmdbClient
     * @param LanguageRepository $languageRepository
     */
    public function handle(TmdbClient $tmdbClient, LanguageRepository $languageRepository): void
    {
        $this->line('Fetching languages from TMDB...');
        $languageResponse = $tmdbClient->getLanguages();
        if ($languageResponse->hasBeenSuccessful() === false) {
            return;
        }
        foreach ($languageResponse->getLanguages() as $language) {
            $languageRepository->create($language->toArray());
        }
        $this->info('Successfully fetched and stored languages.');
    }
}
