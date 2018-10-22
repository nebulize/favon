<?php

namespace Favon\Television\Commands\Cronjobs;

use Psr\Log\LoggerInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Favon\Television\Repositories\TvShowRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateImdbRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favon:update:tv:imdb';

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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * UpdateImdbRatings constructor.
     * @param TvShowRepository $tvShowRepository
     * @param LoggerInterface $logger
     */
    public function __construct(TvShowRepository $tvShowRepository, LoggerInterface $logger)
    {
        $this->tvShowRepository = $tvShowRepository;
        $this->logger = $logger;
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
        $this->updateRatings();
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
     * Go line by line through the extracted ratings and update them in database.
     */
    protected function updateRatings(): void
    {
        DB::connection()->disableQueryLog();
        $handle = fopen(storage_path('api/ratings.tsv'), 'rb');
        $count_matched = 0;
        $count_skipped = 0;
        while (feof($handle) === false) {
            $entry = fgetcsv($handle, 0, "\t");
            try {
                $tvShow = $this->tvShowRepository->find([
                    'imdb_id' => trim($entry[0]),
                ]);
                $score = trim($entry[1]);
                $votes = trim($entry[2]);
                if ($score) {
                    $tvShow->imdb_score = (float) $score;
                }
                if ($votes) {
                    $tvShow->imdb_votes = (int) $votes;
                }
                $this->tvShowRepository->save($tvShow);
                $count_matched++;
            } catch (ModelNotFoundException $e) {
                $count_skipped++;
            }
        }
        fclose($handle);
        $this->logger->info('Skipped '.$count_skipped.' entries and matched '.$count_matched);
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
