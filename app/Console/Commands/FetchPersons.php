<?php

namespace App\Console\Commands;

use App\Repositories\PersonRepository;
use App\Services\TMDBService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Logging\Log;

class FetchPersons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:persons-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all persons from the TMDB API and store them in database (~100h!)';

    /**
     * @var TMDBService
     */
    protected $tmdbService;

    /**
     * @var PersonRepository
     */
    protected $personRepository;

    protected $logger;

    /**
     * FetchPersons constructor.
     * @param TMDBService $tmdbService
     * @param PersonRepository $personRepository
     */
    public function __construct(TMDBService $tmdbService, PersonRepository $personRepository, Log $logger)
    {
        $this->tmdbService = $tmdbService;
        $this->personRepository = $personRepository;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->fetchFile();
        $this->extract();
        $this->fetchPersons();
        $this->deleteFiles();
        return true;
    }

    /**
     * Fetch daily export files from TMDB
     */
    protected function fetchFile()
    {
        $date = Carbon::now();
        $url = 'http://files.tmdb.org/p/exports/person_ids_'.$date->format('m_d_Y').'.json.gz';
        set_time_limit(0);
        $fp = fopen(storage_path('api/person_ids.json.gz'), 'wb+');
        $curlCh = curl_init($url);
        curl_setopt($curlCh, CURLOPT_TIMEOUT, 50);
        curl_setopt($curlCh, CURLOPT_FILE, $fp);
        curl_setopt($curlCh, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curlCh);
        curl_close ($curlCh);
        fclose($fp);
    }

    /**
     * Extract the gzipped file
     */
    protected function extract()
    {
        $sfp = gzopen(storage_path('api/person_ids.json.gz'), 'rb');
        $fp = fopen(storage_path('api/person_ids.json'), 'wb');
        while (!gzeof($sfp)) {
            $string = gzread($sfp, 4096);
            fwrite($fp, $string, \strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
    }

    /**
     * Go line by line through the extracted file and fetch each person
     */
    protected function fetchPersons()
    {
        $handle = fopen(storage_path('api/person_ids.json'), 'rb');
        $count = 0;
        $limit = 10;
        while($count < $limit && !feof($handle)) {
            $entry = json_decode(trim(fgets($handle)));
            $person = $this->tmdbService->getPerson($entry->id);
            $this->personRepository->create($person);
            if (!empty($person['photo'])) {
                $this->tmdbService->fetchImages('profile', $person['photo']);
            }
            if ($count%100 === 0) {
                $this->logger->info('Fetched '.$count.' persons from TMDB API.');
            }
            $count++;
        }
        fclose($handle);
    }

    /**
     * Delete the downloaded files
     */
    protected function deleteFiles()
    {
        unlink(storage_path('api/person_ids.json.gz'));
        unlink(storage_path('api/person_ids.json'));
    }
}
