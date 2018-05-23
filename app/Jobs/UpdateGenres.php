<?php

namespace App\Jobs;

use App\Http\Clients\OMDBClient;
use App\Http\Clients\TMDBClient;
use App\Http\Clients\TVDBClient;
use App\Repositories\GenreRepository;
use App\Repositories\TvShowRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateGenres implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $id;

    /**
     * UpdateGenres constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param TvShowRepository $tvShowRepository
     * @param TMDBClient $tmdbClient
     * @param TVDBClient $tvdbClient
     * @param OMDBClient $omdbClient
     * @param GenreRepository $genreRepository
     */
    public function handle(TvShowRepository $tvShowRepository, TMDBClient $tmdbClient, TVDBClient $tvdbClient,
                           OMDBClient $omdbClient, GenreRepository $genreRepository)
    {
        $tvShow = $tvShowRepository->get($this->id);
        $tmdbResponse = $tmdbClient->getTvShow($tvShow->tmdb_id);
        $genres = $tmdbResponse->getGenres();
        if ($tvShow->tvdb_id !== null) {
            $tvdbResponse = $tvdbClient->get($tvShow->tvdb_id);
            if ($tvdbResponse->hasBeenSuccessful()) {
                $genres = array_merge($genres, $tvdbResponse->getGenres());
            }
        }
        if ($tvShow->imdb_id !== null) {
            $omdbResponse = $omdbClient->get($tvShow->imdb_id);
            if ($omdbResponse->hasBeenSuccessful()) {
                $genres = array_merge($genres, $omdbResponse->getGenres());
            }
        }

        // Sync genres
        $genresToSync = [];
        foreach ($genres as $name) {
            // Set genre to anime if it's animation from Japan
            if ($name === 'Animation' && \in_array('JP', $tmdbResponse->getCountries(), true)) {
                $name = 'Anime';
            }
            try {
                $genre = $genreRepository->find(['name' => $name]);
                $genresToSync[] = $genre->id;
            } catch (ModelNotFoundException $e) {
                $genre = $genreRepository->create([
                    'name' => $name,
                ]);
                $genresToSync[] = $genre->id;
            }
        }
        $tvShowRepository->syncGenres($tvShow, $genresToSync);
    }
}
