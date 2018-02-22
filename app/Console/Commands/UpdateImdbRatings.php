<?php

namespace App\Console\Commands;

use App\Jobs\UpdateImdbRatingsChunk;
use App\Repositories\TvShowRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UpdateImdbRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:ratings:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all current IMDB ratings and updates them in out database.';

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * UpdateImdbRatings constructor.
     * @param TvShowRepository $tvShowRepository
     */
    public function __construct(TvShowRepository $tvShowRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('Fetching latest ratings from IMDB...');
        $this->fetchFile();
        $this->line('Extracting...');
        $this->extract();
        $this->info('Successfully extracted the ratings list.');
        $this->line('Updating ratings...');
        $this->splitFiles();
        $this->deleteFiles();
        $this->info('All ratings have been updated and the downloaded files have been deleted.');
    }

    /**
     * Fetch the daily export file from IMDB.
     */
    protected function fetchFile(): void
    {
        $url = 'https://datasets.imdbws.com/title.ratings.tsv.gz';
        set_time_limit(0);
        $fp = fopen(storage_path('api/ratings.tsv.gz'), 'wb+');
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
        $sfp = gzopen(storage_path('api/ratings.tsv.gz'), 'rb');
        $fp = fopen(storage_path('api/ratings.tsv'), 'wb');
        while (! gzeof($sfp)) {
            $string = gzread($sfp, 4096);
            fwrite($fp, $string, \strlen($string));
        }
        gzclose($sfp);
        fclose($fp);
    }

    /**
     * Split the large TSV files into smaller chunks (10k lines each) and dispatch job for processing each one.
     * Need to do this since otherwise there are memory leaks with PHP when processing it all at once.
     * Even when disabling Eloquent query log.
     */
    protected function splitFiles(): void
    {
        $handle = fopen(storage_path('api/ratings.tsv'), 'rb');
        fgetcsv($handle, 0, "\t"); // Ignore first line
        $rowCount = 1;
        $fileCount = 1;
        $out = fopen(storage_path('api/ratings-'.$fileCount++.'.tsv'), 'wb');
        while (feof($handle) === false) {
            if (($rowCount % 2000) === 0) {
                fclose($out);
                UpdateImdbRatingsChunk::dispatch('ratings-'.($fileCount - 1).'.tsv');
                $this->line('Dispatched '.($fileCount -1).' jobs.');
                $out = fopen(storage_path('api/ratings-'.$fileCount++.'.tsv'), 'wb');
            }
            $data = fgetcsv($handle, 0, "\t");
            if ($data) {
                fputcsv($out, $data, "\t");
            }
            $rowCount++;
        }
        fclose($out);
        // Last entry reached.
        UpdateImdbRatingsChunk::dispatch('ratings-'.($fileCount - 1).'.tsv');
        $this->line('Dispatched '.($fileCount - 1).' jobs.');
        fclose($handle);
    }

    /**
     * Delete downloaded files.
     */
    protected function deleteFiles(): void
    {
        unlink(storage_path('api/ratings.tsv.gz'));
        unlink(storage_path('api/ratings.tsv'));
    }


}
