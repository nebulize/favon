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
        $handle = fopen(storage_path('api/'.$this->path), 'rb');
        while (feof($handle) === false) {
            $entry = fgetcsv($handle, 0, "\t");
            try {
                $tvShow = $tvShowRepository->find([
                    'imdb_id' => $entry[0]
                ]);
            } catch (ModelNotFoundException $e) {
                continue;
            }
            $tvShow->imdb_score = (float) $entry[1];
            $tvShow->imdb_votes = (int) $entry[2];
            $tvShowRepository->save($tvShow);
        }
        fclose($handle);
        unlink(storage_path('api/'.$this->path));
    }
}
