<?php

namespace Favon\Media\Commands\Initialize;

use Illuminate\Console\Command;
use Favon\Media\Http\Clients\TmdbMediaClient;
use Favon\Media\Repositories\CountryRepository;

class FetchCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:fetch:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all available countries from the TMDB API and stores them in database';

    /**
     * Execute the console command.
     *
     * @param TmdbMediaClient $tmdbClient
     * @param CountryRepository $countryRepository
     */
    public function handle(TmdbMediaClient $tmdbClient, CountryRepository $countryRepository): void
    {
        $this->line('Fetching countries from TMDB...');
        $countryResponse = $tmdbClient->getCountries();
        if ($countryResponse->hasBeenSuccessful() === false) {
            return;
        }
        foreach ($countryResponse->getCountries() as $country) {
            $countryRepository->create($country->toArray());
        }
        $this->info('Successfully fetched and stored countries.');
    }
}
