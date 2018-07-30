<?php

namespace Favon\Television\Services\Api;

use Carbon\Carbon;
use Favon\Application\Exceptions\NoAPIResultsFoundException;
use Favon\Application\Exceptions\TvSeasonWasDeletedException;
use Favon\Media\Repositories\SeasonRepository;
use Favon\Television\Events\TvSeasonUpdated;
use Favon\Television\Http\Clients\TmdbTvClient;
use Favon\Television\Http\Responses\TMDB\Models\REpisode;
use Favon\Television\Models\TvSeason;
use Favon\Television\Models\TvShow;
use Favon\Television\Repositories\EpisodeRepository;
use Favon\Television\Repositories\TvSeasonRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;

class TvSeasonFetchingService
{
    /**
     * @var TmdbTvClient
     */
    protected $tmdbClient;

    /**
     * @var SeasonRepository
     */
    protected $seasonRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * @var EpisodeRepository
     */
    protected $episodeRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TvSeasonFetchingService constructor.
     * @param TmdbTvClient $tmdbTvClient
     * @param SeasonRepository $seasonRepository
     * @param TvSeasonRepository $tvSeasonRepository
     * @param EpisodeRepository $episodeRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        TmdbTvClient $tmdbTvClient,
        SeasonRepository $seasonRepository,
        TvSeasonRepository $tvSeasonRepository,
        EpisodeRepository $episodeRepository,
        LoggerInterface $logger
    )
    {
        $this->tmdbClient = $tmdbTvClient;
        $this->seasonRepository = $seasonRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
        $this->episodeRepository = $episodeRepository;
        $this->logger = $logger;
    }

    /**
     * Fetch a single tv season by its tv show id and season number, and store it in database.
     *
     * @param TvShow $tvShow
     * @param int $number
     *
     * @throws NoAPIResultsFoundException
     *
     * @return TvSeason
     */
    public function fetch(TvShow $tvShow, int $number): TvSeason
    {
        // Fetch TV Season
        $tvSeasonResponse = $this->tmdbClient->getTvSeason($tvShow->tmdb_id, $number);
        if ($tvSeasonResponse->hasBeenSuccessful() === false) {
            throw new NoAPIResultsFoundException(__CLASS__.': No results found for id '.$tvShow->tmdb_id. ' S#'.$number);
        }
        $attributes = $tvSeasonResponse->toArray();
        $attributes['season_id'] = $this->getSeason($tvSeasonResponse->getFirstAired());
        $attributes['tv_show_id'] = $tvShow->id;
        $tvSeason = $this->tvSeasonRepository->create($attributes);

        // Save episodes
        foreach ($tvSeasonResponse->getEpisodes() as $episode) {
            $this->episodeRepository->create([
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
     * Update an existing tv season.
     * There are 3 scenarios when updating a tv season:
     * - New TV season, we don't have an entry with this id yet.
     * - Existing TV season, but deleted on TMDB: will return a 404. We will delete it as well.
     * - Existing TV season, updated on TMDB: we will update it in our database.
     *
     * @param TvShow $tvShow
     * @param int $number
     *
     * @throws NoAPIResultsFoundException
     * @throws TvSeasonWasDeletedException
     *
     * @return TvSeason
     */
    public function update(TvShow $tvShow, int $number): TvSeason
    {
        // Grab the existing season from our database
        try {
            $tvSeason = $this->tvSeasonRepository->find([
                'tv_show_id' => $tvShow->id,
                'number' => $number,
            ]);
        } catch (ModelNotFoundException $exception) {
            $this->logger->info('Creating new season: '.$tvShow->name.' Season '.$number);
            // We don't have this season in our database yet!
            return $this->fetch($tvShow, $number);
        }

        // Fetch TV Season
        $tvSeasonResponse = $this->tmdbClient->getTvSeason($tvShow->tmdb_id, $number);

        if ($tvSeasonResponse->getHttpStatusCode() === 404) {
            // Scenario 2
            $this->tvSeasonRepository->delete($tvSeason);
            throw new TvSeasonWasDeletedException(__CLASS__.': Tv season with id '.$tvShow->tmdb_id.' S#'.$number.' was deleted from TMDB.');
        }

        if ($tvSeasonResponse->hasBeenSuccessful() === false) {
            throw new NoAPIResultsFoundException(__CLASS__.': No results found for tv season with id '.$tvShow->tmdb_id.' and number '.$number);
        }

        $attributes = $tvSeasonResponse->toArray();
        $attributes['season_id'] = $this->getSeason($tvSeasonResponse->getFirstAired());
        $tvSeason->fill($attributes);
        $tvSeason->save();

        $this->logger->info('Updated an existing season: '.$tvShow->name.' Season '.$number);

        // Save episodes
        foreach ($tvSeasonResponse->getEpisodes() as $episode) {
            $this->updateEpisode($tvSeason, $episode);
        }

        event(new TvSeasonUpdated($tvSeason));

        return $tvSeason;
    }

    /**
     * Get the season id, if possible (if the tv season has a first_aired date).
     *
     * @param Carbon|null $firstAired
     *
     * @return int|null
     */
    protected function getSeason(?Carbon $firstAired): ?int
    {
        // Set season if possible
        if ($firstAired instanceof Carbon) {
            try {
                $season = $this->seasonRepository->find([
                    'date' => $firstAired,
                    'overflow' => true,
                ]);
            } catch (ModelNotFoundException $exception) {
                $season = $this->seasonRepository->create([
                    'date' => $firstAired,
                    'overflow' => true,
                ]);
            }

            return $season->id;
        }

        return null;
    }

    /**
     * Update an existing episode in database, or create it if not found.
     *
     * @param TvSeason $tvSeason
     * @param REpisode $episode
     */
    protected function updateEpisode(TvSeason $tvSeason, REpisode $episode): void
    {
        // Grab the existing episode from our database
        try {
            $episode = $this->episodeRepository->find([
                'tv_season_id' => $tvSeason->id,
                'number' => $episode->getNumber(),
            ]);
        } catch (ModelNotFoundException $exception) {
            // We don't have this episode in our database yet!
            $this->episodeRepository->create([
                'number' => $episode->getNumber(),
                'name' => $episode->getName(),
                'first_aired' => $episode->getFirstAired(),
                'plot' => $episode->getPlot(),
                'tmdb_id' => $episode->getTmdbId(),
                'tv_season_id' => $tvSeason->id,
            ]);

            return;
        }

        $episode->fill($episode->toArray());
        $episode->save();
    }

}
