<?php

namespace Favon\Television\Services\Api;

use Psr\Log\LoggerInterface;
use Favon\Television\Models\TvShow;
use Favon\Media\Repositories\GenreRepository;
use Favon\Television\Http\Clients\OmdbTvClient;
use Favon\Television\Http\Clients\TmdbTvClient;
use Favon\Television\Http\Clients\TvdbTvClient;
use Favon\Television\Repositories\AirDayRepository;
use Favon\Television\Repositories\RatingRepository;
use Favon\Television\Repositories\TvShowRepository;
use Favon\Television\Repositories\NetworkRepository;
use Favon\Television\Http\Responses\TMDB\Models\RSeason;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Favon\Application\Exceptions\TvShowWasDeletedException;
use Favon\Application\Exceptions\NoAPIResultsFoundException;
use Favon\Television\Repositories\ProductionStatusRepository;

class TvShowFetchingService
{
    /**
     * @var TmdbTvClient
     */
    protected $tmdbClient;

    /**
     * @var OmdbTvClient
     */
    protected $omdbClient;

    /**
     * @var TvdbTvClient
     */
    protected $tvdbClient;

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var NetworkRepository
     */
    protected $networkRepository;

    /**
     * @var GenreRepository
     */
    protected $genreRepository;

    /**
     * @var AirDayRepository
     */
    protected $airDayRepository;

    /**
     * @var RatingRepository
     */
    protected $ratingRepository;

    /**
     * @var ProductionStatusRepository
     */
    protected $productionStatusRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TvShowFetchingService constructor.
     * @param TmdbTvClient $tmdbTvClient
     * @param OmdbTvClient $omdbTvClient
     * @param TvdbTvClient $tvdbTvClient
     * @param TvShowRepository $tvShowRepository
     * @param NetworkRepository $networkRepository
     * @param GenreRepository $genreRepository
     * @param AirDayRepository $airDayRepository
     * @param RatingRepository $ratingRepository
     * @param ProductionStatusRepository $productionStatusRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        TmdbTvClient $tmdbTvClient,
        OmdbTvClient $omdbTvClient,
        TvdbTvClient $tvdbTvClient,
        TvShowRepository $tvShowRepository,
        NetworkRepository $networkRepository,
        GenreRepository $genreRepository,
        AirDayRepository $airDayRepository,
        RatingRepository $ratingRepository,
        ProductionStatusRepository $productionStatusRepository,
        LoggerInterface $logger
    ) {
        $this->tmdbClient = $tmdbTvClient;
        $this->omdbClient = $omdbTvClient;
        $this->tvdbClient = $tvdbTvClient;
        $this->tvShowRepository = $tvShowRepository;
        $this->networkRepository = $networkRepository;
        $this->genreRepository = $genreRepository;
        $this->airDayRepository = $airDayRepository;
        $this->ratingRepository = $ratingRepository;
        $this->productionStatusRepository = $productionStatusRepository;
        $this->logger = $logger;
    }

    /**
     * Fetch a single tv show by its TMDB id and store it in database.
     * Returns an array with all tv seasons for further processing.
     *
     * @param int $id
     *
     * @throws NoAPIResultsFoundException
     *
     * @return RSeason[]
     */
    public function fetch(int $id): array
    {
        $tvShowResponse = $this->tmdbClient->getTvShow($id);
        if ($tvShowResponse->hasBeenSuccessful() === false || $tvShowResponse->getName() === null) {
            throw new NoAPIResultsFoundException(__CLASS__.': No results found for TMDB id '.$id);
        }
        $tvShow = $this->tvShowRepository->create($tvShowResponse->toArray());

        $this->setProductionStatus($tvShow, $tvShowResponse->getStatus());
        $this->syncNetworks($tvShow, $tvShowResponse->getNetworks());
        $this->syncLanguages($tvShow, $tvShowResponse->getLanguages());
        $this->syncCountries($tvShow, $tvShowResponse->getCountries());
        $this->fetchIds($tvShow);

        $genres = $this->fetchOmdbData($tvShow, $tvShowResponse->getGenres());
        $genres = $this->fetchTvdbData($tvShow, $genres);
        $this->syncGenres($tvShow, $genres, $tvShowResponse->getCountries());

        $this->setSummary($tvShow, $tvShowResponse->getPlot());

        // Save updated tv show
        $this->tvShowRepository->save($tvShow);

        return $tvShowResponse->getSeasons();
    }

    /**
     * Update an existing tv show. Returns an array with all tv seasons for further processing.
     * There are 3 scenarios when updating a tv show:
     * - New TV Show, we don't have an entry with this id yet.
     * - Existing TV Show, but deleted on TMDB: will return a 404. We will delete it as well.
     * - Existing Tv Show, updated on TMDB: we will update it in our database.
     *
     * @param int $id
     *
     * @throws NoAPIResultsFoundException
     * @throws TvShowWasDeletedException
     *
     * @returns RSeason[]
     */
    public function update(int $id): array
    {
        try {
            $tvShow = $this->tvShowRepository->find([
                'tmdb_id' => $id,
            ]);
        } catch (ModelNotFoundException $exception) {
            // Scenario 1
            $this->logger->warning('Tv show with id '.$id.' not found, fetching it.');

            return $this->fetch($id);
        }

        $tvShowResponse = $this->tmdbClient->getTvShow($tvShow->tmdb_id);

        if ($tvShowResponse->getHttpStatusCode() === 404) {
            // Scenario 2
            throw new TvShowWasDeletedException(__CLASS__.': Tv show with id '.$tvShow->tmdb_id.' was deleted from TMDB.');
        }

        if ($tvShowResponse->hasBeenSuccessful() === false || $tvShowResponse->getName() === null) {
            throw new NoAPIResultsFoundException(__CLASS__.': No results found for tv show with id '.$tvShow->tmdb_id);
        }

        // Scenario 3
        $tvShow->fill($tvShowResponse->toArray());
        $this->tvShowRepository->save($tvShow);

        $this->syncNetworks($tvShow, $tvShowResponse->getNetworks());
        $this->syncLanguages($tvShow, $tvShowResponse->getLanguages());
        $this->syncCountries($tvShow, $tvShowResponse->getCountries());
        $this->fetchIds($tvShow);

        $genres = $this->fetchOmdbData($tvShow, $tvShowResponse->getGenres());
        $genres = $this->fetchTvdbData($tvShow, $genres);
        $this->syncGenres($tvShow, $genres, $tvShowResponse->getCountries());
        $this->setSummary($tvShow, $tvShowResponse->getPlot());

        // Save updated tv show
        $this->tvShowRepository->save($tvShow);

        return $tvShowResponse->getSeasons();
    }

    /**
     * Set the production status for a tv show.
     *
     * @param TvShow $tvShow
     * @param null|string $status
     */
    protected function setProductionStatus(TvShow $tvShow, ?string $status): void
    {
        if ($status === null) {
            return;
        }

        try {
            $productionStatus = $this->productionStatusRepository->find(['name' => $status]);
        } catch (ModelNotFoundException $exception) {
            $productionStatus = $this->productionStatusRepository->create(['name' => $status]);
        }

        $tvShow->production_status_id = $productionStatus->id;
    }

    /**
     * Add and store networks to the tv show.
     *
     * @param TvShow $tvShow
     * @param array|null $networks
     */
    protected function syncNetworks(TvShow $tvShow, ?array $networks): void
    {
        $networksToSync = [];
        foreach ($networks as $name) {
            try {
                $network = $this->networkRepository->find(['name' => $name]);
            } catch (ModelNotFoundException $exception) {
                $network = $this->networkRepository->create(['name' => $name]);
            }
            $networksToSync[] = $network->id;
        }
        $this->tvShowRepository->syncNetworks($tvShow, $networksToSync);
    }

    /**
     * Sync languages for a tv show.
     *
     * @param TvShow $tvShow
     * @param array|null $languages
     */
    protected function syncLanguages(TvShow $tvShow, ?array $languages): void
    {
        if ($languages !== null) {
            $this->tvShowRepository->syncLanguages($tvShow, $languages);
        }
    }

    /**
     * Sync countries for a tv show.
     *
     * @param TvShow $tvShow
     * @param array|null $countries
     */
    protected function syncCountries(TvShow $tvShow, ?array $countries): void
    {
        if ($countries !== null) {
            $this->tvShowRepository->syncCountries($tvShow, $countries);
        }
    }

    /**
     * Fetch external IDs.
     *
     * @param TvShow $tvShow
     */
    protected function fetchIds(TvShow $tvShow): void
    {
        // Fetch Ids
        $tvShowIdsResponse = $this->tmdbClient->getTvShowIds($tvShow->tmdb_id);
        if ($tvShowIdsResponse->hasBeenSuccessful()) {
            $tvShow->imdb_id = $tvShowIdsResponse->getImdbId();
            $tvShow->tvdb_id = $tvShowIdsResponse->getTvdbId();
        }
    }

    /**
     * Fetch OMDB data and on success merge the genres with the results from TMDB.
     *
     * @param TvShow $tvShow
     * @param array $genres
     * @param bool $withScore
     *
     * @return array
     */
    protected function fetchOmdbData(TvShow $tvShow, array $genres, $withScore = true): array
    {
        if ($tvShow->imdb_id !== null) {
            $omdbResponse = $this->omdbClient->get($tvShow->imdb_id);

            if ($omdbResponse->hasBeenSuccessful()) {
                $tvShow->summary = $omdbResponse->getSummary();
                if ($withScore === true) {
                    $tvShow->imdb_score = $omdbResponse->getImdbScore();
                    $tvShow->imdb_votes = $omdbResponse->getImdbVotes();
                }

                // Merge genres
                $genres = array_merge($genres, $omdbResponse->getGenres());
            }
        }

        return $genres;
    }

    /**
     * Fetch TVDB data and on success merge the genres with thr results from TMDB / OMDB.
     *
     * @param TvShow $tvShow
     * @param array $genres
     *
     * @return array
     */
    protected function fetchTvdbData(TvShow $tvShow, array $genres): array
    {
        if ($tvShow->tvdb_id !== null) {
            $tvdbResponse = $this->tvdbClient->get($tvShow->tvdb_id);

            if ($tvdbResponse->hasBeenSuccessful()) {
                $tvShow->air_time = $tvdbResponse->getAirTime();

                if ($tvdbResponse->getAirDay() !== null) {
                    try {
                        $airDay = $this->airDayRepository->find(['name' => $tvdbResponse->getAirDay()]);
                    } catch (ModelNotFoundException $exception) {
                        $airDay = $this->airDayRepository->create(['name' => $tvdbResponse->getAirDay()]);
                    }
                    $tvShow->tv_air_day_id = $airDay->id;
                }

                if ($tvdbResponse->getRating() !== null) {
                    try {
                        $rating = $this->ratingRepository->find(['name' => $tvdbResponse->getRating()]);
                    } catch (ModelNotFoundException $exception) {
                        $rating = $this->ratingRepository->create(['name' => $tvdbResponse->getRating()]);
                    }
                    $tvShow->tv_rating_id = $rating->id;
                }

                $genres = array_merge($genres, $tvdbResponse->getGenres());
            }
        }

        return $genres;
    }

    /**
     * Sync the genres for a tv show.
     *
     * @param TvShow $tvShow
     * @param array $genres
     * @param array $countries
     */
    protected function syncGenres(TvShow $tvShow, array $genres, array $countries): void
    {
        $genresToSync = [];
        foreach ($genres as $name) {
            // Set genre to anime if it's animation from Japan
            if ($name === 'Animation' && \in_array('JP', $countries, true)) {
                $name = 'Anime';
            }
            try {
                $genre = $this->genreRepository->find(['name' => $name]);
            } catch (ModelNotFoundException $exception) {
                $genre = $this->genreRepository->create([
                    'name' => $name,
                ]);
            }
            $genresToSync[] = $genre->id;
        }
        $this->tvShowRepository->syncGenres($tvShow, $genresToSync);
    }

    /**
     * Set summary to plot if summary is empty.
     *
     * @param TvShow $tvShow
     * @param string $plot
     */
    protected function setSummary(TvShow $tvShow, ?string $plot): void
    {
        if ($tvShow->summary === null || $tvShow->summary === '' || $tvShow->summary === 'N/A') {
            $tvShow->summary = $plot;
        }
    }
}
