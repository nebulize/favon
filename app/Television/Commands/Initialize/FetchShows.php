<?php

namespace Favon\Television\Commands\Initialize;

use Carbon\Carbon;
use Favon\Television\Jobs\FetchShow;
use Illuminate\Console\Command;

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
     * Execute the command.
     */
    public function handle(): void
    {
        $this->line('Fetching latest tv shows export from TMDB...');
        $this->fetchFile();
        $this->line('Extracting...');
        $this->extract();
        $this->info('Successfully extracted the tv show list.');
        $this->line('Queueing fetching jobs...');
        $this->fetchShows();
        $this->deleteFiles();
        $this->info('Deleted downloaded files. The fetching of tv shows is queued and will take around 80 hours to complete');
    }

    /**
     * Fetch the daily export file for TV shows from TMDB and store it locally.
     */
    protected function fetchFile(): void
    {
        $date = Carbon::now();
        $url = 'http://files.tmdb.org/p/exports/tv_series_ids_'.$date->format('m_d_Y').'.json.gz';
        set_time_limit(0);
        $fp = fopen(storage_path('api/tv_series_ids.json.gz'), 'wb+');
        $curlCh = curl_init($url);
        curl_setopt($curlCh, CURLOPT_TIMEOUT, 50);
        curl_setopt($curlCh, CURLOPT_FILE, $fp);
        curl_setopt($curlCh, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curlCh);
        curl_close($curlCh);
        fclose($fp);
    }

    /**
     * Extract the downloaded export file.
     */
    protected function extract(): void
    {
        $sfp = gzopen(storage_path('api/tv_series_ids.json.gz'), 'rb');
        $fp = fopen(storage_path('api/tv_series_ids.json'), 'wb');
        while (! gzeof($sfp)) {
            $string = gzread($sfp, 4096);
            fwrite($fp, $string, \strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
    }

    /**
     * Go through the extracted list line by line and dispatch a job for each entry.
     */
    protected function fetchShows(): void
    {
        $handle = fopen(storage_path('api/tv_series_ids.json'), 'rb');
        while (! feof($handle)) {
            $entry = json_decode(trim(fgets($handle)));
            if (\is_object($entry) && property_exists($entry, 'id')) {
                FetchShow::dispatch($entry->id);
            }
        }
        fclose($handle);
    }

    /**
     * Delete the downloaded files since we don't need them anymore.
     */
    protected function deleteFiles(): void
    {
        unlink(storage_path('api/tv_series_ids.json.gz'));
        unlink(storage_path('api/tv_series_ids.json'));
    }
}
