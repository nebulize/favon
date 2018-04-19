<?php

namespace App\Services;

use App\Events\TvSeasonUpdated;
use Carbon\Carbon;
use App\Models\TVShow;
use App\Models\TVSeason;
use App\Http\Clients\OMDBClient;
use App\Http\Clients\TMDBClient;
use App\Http\Clients\TVDBClient;
use Illuminate\Support\Facades\Log;
use App\Repositories\GenreRepository;
use App\Repositories\VideoRepository;
use App\Repositories\PersonRepository;
use App\Repositories\SeasonRepository;
use App\Repositories\TvShowRepository;
use App\Repositories\NetworkRepository;
use App\Repositories\TvSeasonRepository;
use App\Repositories\TvEpisodeRepository;
use App\Http\Responses\TMDB\Models\REpisode;
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

    /**
     * @var SeasonRepository
     */
    protected $seasonRepository;

    /**
     * @var TvEpisodeRepository
     */
    protected $tvEpisodeRepository;

    /**
     * @var VideoRepository
     */
    protected $videoRepository;

    /**
     * @var PersonRepository
     */
    protected $personRepository;

    /**
     * @var NetworkRepository
     */
    protected $networkRepository;

    public function __construct(TMDBClient $tmdbClient, OMDBClient $omdbClient, TVDBClient $tvdbClient,
                                TvShowRepository $tvShowRepository, GenreRepository $genreRepository,
                                TvSeasonRepository $tvSeasonRepository, SeasonRepository $seasonRepository,
                                TvEpisodeRepository $tvEpisodeRepository, VideoRepository $videoRepository,
                                PersonRepository $personRepository, NetworkRepository $networkRepository)
    {
        $this->tmdbClient = $tmdbClient;
        $this->omdbClient = $omdbClient;
        $this->tvdbClient = $tvdbClient;
        $this->tvShowRepository = $tvShowRepository;
        $this->genreRepository = $genreRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->seasonRepository = $seasonRepository;
        $this->tvEpisodeRepository = $tvEpisodeRepository;
        $this->videoRepository = $videoRepository;
        $this->personRepository = $personRepository;
        $this->networkRepository = $networkRepository;
    }

    /**
     * Fetch and store all data for a TV Show.
     *
     * @param int $id TMDB id
     * @return TVShow|null
     */
    public function fetchTvShow(int $id): ?TVShow
    {
        $tvShowResponse = $this->tmdbClient->getTvShow($id);
        if ($tvShowResponse->hasBeenSuccessful() === false || $tvShowResponse->getName() === null) {
            return null;
        }
        $tvShow = $this->tvShowRepository->create($tvShowResponse->toArray());

        // Sync networks
        $networks = [];
        foreach ($tvShowResponse->getNetworks() as $name) {
            try {
                $network = $this->networkRepository->find(['name' => $name]);
            } catch (ModelNotFoundException $e) {
                $network = $this->networkRepository->create(['name' => $name]);
            }
            $networks[] = $network->id;
        }
        $this->tvShowRepository->syncNetworks($tvShow, $networks);

        // Sync languages
        if ($tvShowResponse->getLanguages() !== null) {
            $this->tvShowRepository->syncLanguages($tvShow, $tvShowResponse->getLanguages());
        }

        // Sync countries
        if ($tvShowResponse->getCountries() !== null) {
            $this->tvShowRepository->syncCountries($tvShow, $tvShowResponse->getCountries());
        }

        // Fetch Ids
        $tvShowIdsResponse = $this->tmdbClient->getTvShowIds($id);
        if ($tvShowIdsResponse->hasBeenSuccessful()) {
            $tvShow->imdb_id = $tvShowIdsResponse->getImdbId();
            $tvShow->tvdb_id = $tvShowIdsResponse->getTvdbId();
        }

        // Fetch data from OMDB
        if ($tvShow->imdb_id !== null) {
            $omdbResponse = $this->omdbClient->get($tvShow->imdb_id);

            if ($omdbResponse->hasBeenSuccessful()) {
                $tvShow->summary = $omdbResponse->getSummary();
                $tvShow->imdb_score = $omdbResponse->getImdbScore();
                $tvShow->imdb_votes = $omdbResponse->getImdbVotes();

                // Sync genres
                $genres = [];
                foreach ($omdbResponse->getGenres() as $name) {
                    if ($name === 'N/A' || $name === 'Adult') {
                        continue;
                    }
                    // Set genre to anime if it's animation from Japan
                    if ($name === 'Animation' && \in_array('JP', $tvShowResponse->getCountries(), true)) {
                        $name = 'Anime';
                    }
                    try {
                        $genre = $this->genreRepository->find(['name' => $name]);
                        $genres[] = $genre->id;
                    } catch (ModelNotFoundException $e) {
                        $genre = $this->genreRepository->create([
                            'name' => $name,
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

        if ($tvShow->summary === null || $tvShow->summary === '' || $tvShow->summary === 'N/A') {
            $tvShow->summary = $tvShowResponse->getPlot();
        }

        // Save updated tv show
        $this->tvShowRepository->save($tvShow);

        // Fetch tv season
        foreach ($tvShowResponse->getSeasons() as $season) {
            $tvSeason = $this->fetchTvSeason($tvShow, $season->getNumber());
            if ($tvSeason !== null) {
                $this->fetchTvSeasonVideos($tvShow, $tvSeason);
                $this->fetchTvSeasonCredits($tvShow, $tvSeason);
            }
        }

        return $tvShow;
    }

    /**
     * Fetch and store all data for a TV Season.
     *
     * @param TVShow $tvShow
     * @param int $number
     * @return TVSeason|null
     */
    public function fetchTvSeason(TVShow $tvShow, int $number): ?TVSeason
    {
        // Fetch TV Season
        $tvSeasonResponse = $this->tmdbClient->getTvSeason($tvShow->tmdb_id, $number);
        if ($tvSeasonResponse->hasBeenSuccessful() === false) {
            return null;
        }
        $attributes = $tvSeasonResponse->toArray();

        // Set season if possible
        if ($tvSeasonResponse->getFirstAired() instanceof Carbon) {
            try {
                $season = $this->seasonRepository->find([
                    'date' => $tvSeasonResponse->getFirstAired(),
                    'overflow' => true,
                ]);
            } catch (ModelNotFoundException $e) {
                $season = $this->seasonRepository->create([
                    'date' => $tvSeasonResponse->getFirstAired(),
                    'overflow' => true,
                ]);
            }
            $attributes['season_id'] = $season->id;
        }

        $attributes['tv_show_id'] = $tvShow->id;
        $tvSeason = $this->tvSeasonRepository->create($attributes);

        // Save episodes
        foreach ($tvSeasonResponse->getEpisodes() as $episode) {
            $this->tvEpisodeRepository->create([
                'number' => $episode->getNumber(),
                'name' => $episode->getName(),
                'first_aired' => $episode->getFirstAired(),
                'plot' => $episode->getPlot(),
                'tmdb_id' => $episode->getTmdbId(),
                'tv_season_id' => $tvSeason->id,
            ]);
        }

        event(new TvSeasonUpdated($tvSeason));

        return $tvSeason;
    }

    /**
     * Fetch and store all videos for a tv season.
     *
     * @param TVShow $tvShow
     * @param TVSeason $tvSeason
     */
    public function fetchTvSeasonVideos(TVShow $tvShow, TVSeason $tvSeason): void
    {
        $tvSeasonVideosResponse = $this->tmdbClient->getTvSeasonVideos($tvShow->tmdb_id, $tvSeason->number);
        if ($tvSeasonVideosResponse->hasBeenSuccessful() === false) {
            return;
        }
        foreach ($tvSeasonVideosResponse->getResults() as $result) {
            $this->videoRepository->create([
                'name' => $result->getName(),
                'key' => $result->getKey(),
                'type' => $result->getType(),
                'tv_season_id' => $tvSeason->id,
            ]);
        }
    }

    /**
     * Fetch and store all credits for a tv season.
     *
     * @param TVShow $tvShow
     * @param TVSeason $tvSeason
     */
    public function fetchTvSeasonCredits(TVShow $tvShow, TVSeason $tvSeason): void
    {
        $tvSeasonCreditsResponse = $this->tmdbClient->getTvSeasonCredits($tvShow->tmdb_id, $tvSeason->number);
        if ($tvSeasonCreditsResponse->hasBeenSuccessful() === false) {
            return;
        }
        foreach ($tvSeasonCreditsResponse->getResults() as $result) {
            if ($result->getTmdbPersonId() === null) {
                Log::warning($tvShow->name.' Season '.$tvSeason->number.' failed to fetch credit (IS NULL): '.json_encode($result));
                continue;
            }

            try {
                $person = $this->personRepository->find([
                    'tmdb_id' => $result->getTmdbPersonId(),
                ]);
            } catch (ModelNotFoundException $e) {
                $personResponse = $this->tmdbClient->getPerson($result->getTmdbPersonId());
                if ($personResponse->hasBeenSuccessful() === false) {
                    return;
                }
                $person = $this->personRepository->create($personResponse->toArray());
            }
            $this->tvSeasonRepository->addPerson($tvSeason, $person, $result->toArray());
        }
    }

    /**
     * Update the popularity value of a tv show.
     *
     * @param int $id
     */
    public function updatePopularity(int $id): void
    {
        $tvShowResponse = $this->tmdbClient->getTvShow($id);
        if ($tvShowResponse->hasBeenSuccessful() === false) {
            return;
        }
        try {
            $tvShow = $this->tvShowRepository->find([
                'tmdb_id' => $tvShowResponse->getTmdbId(),
            ]);
        } catch (ModelNotFoundException $e) {
            // Nothing to do.
            return;
        }

        $tvShow->popularity = $tvShowResponse->getPopularity();
        $this->tvShowRepository->save($tvShow);
    }

    public function updateTvShow(int $id): void
    {
        // 3 scenarios:
        // - New TV Show, we don't have an entry with this id yet.
        // - Existing TV Show, but deleted on TMDB: will return a 404. We will delete it as well.
        // - Existing Tv Show, updated on TMDB: we will update it in our database

        try {
            $tvShow = $this->tvShowRepository->find([
                'tmdb_id' => $id,
            ]);
        } catch (ModelNotFoundException $e) {
            // We don't have an entry with this id yet, simply create it
            $this->fetchTvShow($id);
            Log::info('Created new tv show: '.$id);

            return;
        }

        $tvShowResponse = $this->tmdbClient->getTvShow($id);

        if ($tvShowResponse->getHttpStatusCode() === 404) {
            // Show was deleted on TMDB, delete it from our database as well.
            $this->tvShowRepository->delete($tvShow);
            Log::info('Deleted an existing tv show: '.$tvShow->name);

            return;
        }

        if ($tvShowResponse->hasBeenSuccessful() === false || $tvShowResponse->getName() === null) {
            return;
        }

        // It's in our database and has just been updated on TMDB. Update it on our end.
        $tvShow->fill($tvShowResponse->toArray());
        $this->tvShowRepository->save($tvShow);

        // Sync networks
        $networks = [];
        foreach ($tvShowResponse->getNetworks() as $name) {
            try {
                $network = $this->networkRepository->find(['name' => $name]);
            } catch (ModelNotFoundException $e) {
                $network = $this->networkRepository->create(['name' => $name]);
            }
            $networks[] = $network->id;
        }
        $this->tvShowRepository->syncNetworks($tvShow, $networks);

        // Sync languages
        if ($tvShowResponse->getLanguages() !== null) {
            $this->tvShowRepository->syncLanguages($tvShow, $tvShowResponse->getLanguages());
        }

        // Sync countries
        if ($tvShowResponse->getCountries() !== null) {
            $this->tvShowRepository->syncCountries($tvShow, $tvShowResponse->getCountries());
        }

        // Fetch Ids
        $tvShowIdsResponse = $this->tmdbClient->getTvShowIds($id);
        if ($tvShowIdsResponse->hasBeenSuccessful()) {
            $tvShow->imdb_id = $tvShowIdsResponse->getImdbId();
            $tvShow->tvdb_id = $tvShowIdsResponse->getTvdbId();
        }

        // Fetch data from OMDB
        if ($tvShow->imdb_id !== null) {
            $omdbResponse = $this->omdbClient->get($tvShow->imdb_id);

            if ($omdbResponse->hasBeenSuccessful()) {
                $tvShow->summary = $omdbResponse->getSummary();

                // Sync genres
                $genres = [];
                foreach ($omdbResponse->getGenres() as $name) {
                    if ($name === 'N/A' || $name === 'Adult') {
                        continue;
                    }
                    // Set genre to anime if it's animation from Japan
                    if ($name === 'Animation' && \in_array('JP', $tvShowResponse->getCountries(), true)) {
                        $name = 'Anime';
                    }
                    try {
                        $genre = $this->genreRepository->find(['name' => $name]);
                        $genres[] = $genre->id;
                    } catch (ModelNotFoundException $e) {
                        $genre = $this->genreRepository->create([
                            'name' => $name,
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

        if ($tvShow->summary === null || $tvShow->summary === '' || $tvShow->summary === 'N/A') {
            $tvShow->summary = $tvShowResponse->getPlot();
        }

        // Save updated tv show
        $this->tvShowRepository->save($tvShow);

        // Fetch tv seasons
        foreach ($tvShowResponse->getSeasons() as $season) {
            $tvSeason = $this->updateTvSeason($tvShow, $season->getNumber());
            if ($tvSeason !== null) {
                $this->videoRepository->deleteForSeason($tvSeason->id);
                $this->fetchTvSeasonVideos($tvShow, $tvSeason);
                $tvSeason->persons()->detach();
                $this->fetchTvSeasonCredits($tvShow, $tvSeason);
            }
        }
        Log::info('Updated an existing tv show: '.$tvShow->name);
    }

    protected function updateTvSeason(TVShow $tvShow, int $number): ?TVSeason
    {
        // Grab the existing season from our database
        try {
            $tvSeason = $this->tvSeasonRepository->find([
                'tv_show_id' => $tvShow->id,
                'number' => $number,
            ]);
        } catch (ModelNotFoundException $e) {
            Log::info('Created new season: '.$tvShow->name.' Season '.$number);
            // We don't have this season in our database yet!
            return $this->fetchTvSeason($tvShow, $number);
        }

        // Fetch TV Season
        $tvSeasonResponse = $this->tmdbClient->getTvSeason($tvShow->tmdb_id, $number);
        if ($tvSeasonResponse->hasBeenSuccessful() === false) {
            if ($tvSeasonResponse->getHttpStatusCode() === 404) {
                Log::info('Deleted a season: '.$tvShow->name.' Season '.$number);
                $this->tvSeasonRepository->delete($tvSeason);
            }

            return null;
        }

        $attributes = $tvSeasonResponse->toArray();

        // Set season if possible
        if ($tvSeasonResponse->getFirstAired() instanceof Carbon) {
            try {
                $season = $this->seasonRepository->find([
                    'date' => $tvSeasonResponse->getFirstAired(),
                    'overflow' => true
                ]);
            } catch (ModelNotFoundException $e) {
                $season = $this->seasonRepository->create([
                    'date' => $tvSeasonResponse->getFirstAired(),
                    'overflow' => true,
                ]);
            }
            $attributes['season_id'] = $season->id;
        }

        $tvSeason->fill($attributes);
        $tvSeason->save();
        Log::info('Updated an existing season: '.$tvShow->name.' Season '.$number);

        // Save episodes
        foreach ($tvSeasonResponse->getEpisodes() as $episode) {
            $this->updateEpisode($tvSeason, $episode);
        }

        event(new TvSeasonUpdated($tvSeason));

        return $tvSeason;
    }

    private function updateEpisode(TVSeason $tvSeason, REpisode $episode): void
    {
        // Grab the existing episode from our database
        try {
            $tvEpisode = $this->tvEpisodeRepository->find([
                'tv_season_id' => $tvSeason->id,
                'number' => $episode->getNumber(),
            ]);
        } catch (ModelNotFoundException $e) {
            // We don't have this episode in our database yet!
            $this->tvEpisodeRepository->create([
                'number' => $episode->getNumber(),
                'name' => $episode->getName(),
                'first_aired' => $episode->getFirstAired(),
                'plot' => $episode->getPlot(),
                'tmdb_id' => $episode->getTmdbId(),
                'tv_season_id' => $tvSeason->id,
            ]);

            return;
        }

        $tvEpisode->fill($episode->toArray());
        $tvEpisode->save();
    }
}
