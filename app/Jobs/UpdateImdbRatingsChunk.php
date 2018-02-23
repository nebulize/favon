<?php

namespace App\Jobs;

use App\Repositories\TvShowRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateImdbRatingsChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $path;

    /**
     * UpdateImdbRatingsChunk constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param TvShowRepository $tvShowRepository
     */
    public function handle(TvShowRepository $tvShowRepository): void
    {
        DB::connection()->disableQueryLog();
        Log::info('Processing IMDB Chunk '.$this->path);
        $handle = fopen(storage_path('api/'.$this->path), 'rb');
        while (feof($handle) === false) {
            $entry = fgetcsv($handle, 0, "\t");
            try {
                $tvShow = $tvShowRepository->find([
                    'imdb_id' => trim($entry[0])
                ]);
                Log::info('Matched show with id '.$tvShow->imdb_id.' as '.$entry[0]);
                $tvShow->imdb_score = (float) trim($entry[1]);
                $tvShow->imdb_votes = (int) trim($entry[2]);
                $tvShowRepository->save($tvShow);
            } catch (ModelNotFoundException $e) {
                // Nothing to do
            }
        }
        fclose($handle);
//        unlink(storage_path('api/'.$this->path));
    }
}
