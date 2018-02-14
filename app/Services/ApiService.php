<?php

namespace App\Services;


use App\Http\Clients\OMDBClient;
use App\Http\Clients\TMDBClient;
use App\Http\Clients\TVDBClient;
use App\Repositories\GenreRepository;
use App\Repositories\TvSeasonRepository;
use App\Repositories\TvShowRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiService
{
    /**
     * @var TMDBClient
     */
    protected $tmdbClient;

    /**
     * @var OMDBClient
     */
    protected $omdbClient;

    /**
     * @var TVDBClient
     */
    protected $tvdbClient;

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * @var GenreRepository
     */
    protected $genreRepository;

    public function __construct(TMDBClient $tmdbClient, OMDBClient $omdbClient, TVDBClient $tvdbClient,
                                TvShowRepository $tvShowRepository, GenreRepository $genreRepository,
                                TvSeasonRepository $tvSeasonRepository)
    {
        $this->tmdbClient = $tmdbClient;
        $this->omdbClient = $omdbClient;
        $this->tvdbClient = $tvdbClient;
        $this->tvShowRepository = $tvShowRepository;
        $this->genreRepository = $genreRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
    }

    /**
     * Fetch and store all data for a TV Show.
     *
     * @param int $id
     */
    public function fetchTvShow(int $id): void
    {
        $tvShowResponse = $this->tmdbClient->getTvShow($id);
        if ($tvShowResponse->hasBeenSuccessful() === false) {
            return;
        }
        $tvShow = $this->tvShowRepository->create($tvShowResponse->toArray());

        // Sync languages
        if ($tvShowResponse->getLanguages() !== null) {
            $this->tvShowRepository->syncLanguages($tvShow, $tvShowResponse->getLanguages());
        }

        // Fetch Ids
        $tvShowIdsResponse = $this->tmdbClient->getTvShowIds($id);
        if ($tvShowIdsResponse !== null) {
            $tvShow->imdb_id = $tvShowIdsResponse->getImdbId();
            $tvShow->tvdb_id = $tvShowIdsResponse->getTvdbId();
        }

        // Fetch data from OMDB
        if ($tvShow->imdb_id !== null)  {
            $omdbResponse = $this->omdbClient->get($tvShow->imdb_id);

            if ($omdbResponse->hasBeenSuccessful()) {
                $tvShow->summary = $omdbResponse->getSummary();
                $tvShow->country = $omdbResponse->getCountry();
                $tvShow->imdb_score = $omdbResponse->getImdbScore();
                $tvShow->imdb_votes = $omdbResponse->getImdbVotes();

                // Sync genres
                $genres = array();
                foreach ($omdbResponse->getGenres() as $name) {
                    try {
                        $genre = $this->genreRepository->find(['name' => $name]);
                        $genres[] = $genre->id;
                    } catch (ModelNotFoundException $e) {
                        $genre = $this->genreRepository->create([
                            'name' => $name
                        ]);
                        $genres[] = $genre->id;
                    }
                }

                $this->tvShowRepository->syncGenres($tvShow, $genres);
            }
        }

        // Fetch data from TVDB
        if ($tvShow->tvdb_id !== null) {
            $tvdbResponse = $this->tvdbClient->get($tvShow->tvdb_id);

            if ($tvdbResponse->hasBeenSuccessful()) {
                $tvShow->air_time = $tvdbResponse->getAirTime();
                $tvShow->air_day = $tvdbResponse->getAirDay();
            }
        }

        // Save updated tv show
        $this->tvShowRepository->save($tvShow);

        // Fetch seasons
        foreach ($tvShowResponse->getSeasons() as $season) {
            $response = $this->tmdbClient->getTvSeason($tvShow->tmdb_id, $season->season_number);

        }
    }

}