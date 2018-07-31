<?php

namespace Favon\Media\Services;

use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;

class FetchingService
{
    use DispatchesJobs;

    /**
     * Fetch a daily export file from TMDB and store it locally.
     *
     * @param string $type (`persons`, `tv_series`)
     */
    public function fetchFile(string $type): void
    {
        $date = Carbon::now();
        $url = 'http://files.tmdb.org/p/exports/'.$type.'_ids_'.$date->format('m_d_Y').'.json.gz';
        set_time_limit(0);
        $fp = fopen(storage_path('api/'.$type.'_ids.json.gz'), 'wb+');
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
     *
     * @param string $type
     */
    public function extract(string $type): void
    {
        $sfp = gzopen(storage_path('api/'.$type.'_ids.json.gz'), 'rb');
        $fp = fopen(storage_path('api/'.$type.'_ids.json'), 'wb');
        while (! gzeof($sfp)) {
            $string = gzread($sfp, 4096);
            fwrite($fp, $string, \strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
    }

    /**
     * Go through the extracted list line by line and dispatch a job for each entry.
     *
     * @param string $type
     * @param string $job
     */
    public function fetch(string $type, string $job): void
    {
        $handle = fopen(storage_path('api/'.$type.'_ids.json'), 'rb');
        while (! feof($handle)) {
            $entry = json_decode(trim(fgets($handle)));
            if (\is_object($entry) && property_exists($entry, 'id')) {
                $this->dispatch(new $job($entry->id));
            }
        }
        fclose($handle);
    }

    /**
     * Delete the downloaded files since we don't need them anymore.
     *
     * @param string $type
     */
    public function deleteFiles(string $type): void
    {
        unlink(storage_path('api/'.$type.'_ids.json.gz'));
        unlink(storage_path('api/'.$type.'_ids.json'));
    }

}
