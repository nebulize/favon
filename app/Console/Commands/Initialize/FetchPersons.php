<?php

namespace App\Console\Commands\Initialize;

use Carbon\Carbon;
use App\Jobs\FetchPerson;
use Illuminate\Console\Command;

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
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('Fetching latest person export from TMDB...');
        $this->fetchFile();
        $this->line('Extracting...');
        $this->extract();
        $this->info('Successfully extracted the person list.');
        $this->line('Queueing fetching jobs...');
        $this->fetchPersons();
        $this->deleteFiles();
        $this->info('Deleted downloaded files. The fetching of persons is queued and will take around 100 hours to complete');
    }

    /**
     * Fetch daily export files from TMDB.
     */
    protected function fetchFile(): void
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
        curl_close($curlCh);
        fclose($fp);
    }

    /**
     * Extract the gzipped file.
     */
    protected function extract(): void
    {
        $sfp = gzopen(storage_path('api/person_ids.json.gz'), 'rb');
        $fp = fopen(storage_path('api/person_ids.json'), 'wb');
        while (! gzeof($sfp)) {
            $string = gzread($sfp, 4096);
            fwrite($fp, $string, \strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
    }

    /**
     * Go line by line through the extracted file and dispatch a fetch job for each entry.
     */
    protected function fetchPersons(): void
    {
        $handle = fopen(storage_path('api/person_ids.json'), 'rb');
        while (! feof($handle)) {
            $entry = json_decode(trim(fgets($handle)));
            if (\is_object($entry) && property_exists($entry, 'id')) {
                FetchPerson::dispatch($entry->id);
            }
        }
        fclose($handle);
    }

    /**
     * Delete downloaded files.
     */
    protected function deleteFiles(): void
    {
        unlink(storage_path('api/person_ids.json.gz'));
        unlink(storage_path('api/person_ids.json'));
    }
}
