<?php

namespace Favon\Television\Services\Api;

use Favon\Television\Http\Clients\TmdbTvClient;
use Favon\Television\Repositories\TvShowRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Log\LoggerInterface;

class PopularityUpdatingService
{
    /**
     * @var TmdbTvClient
     */
    protected $tmdbClient;

    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * PopularityUpdatingService constructor.
     * @param TmdbTvClient $tmdbClient
     * @param TvShowRepository $tvShowRepository
     * @param LoggerInterface $logger
     */
    public function __construct(TmdbTvClient $tmdbClient, TvShowRepository $tvShowRepository, LoggerInterface $logger)
    {
        $this->tmdbClient = $tmdbClient;
        $this->tvShowRepository = $tvShowRepository;
        $this->logger = $logger;
    }

    /**
     * Update the popularity value for a given tv show with the current value from TMDB.
     *
     * @param int $id
     */
    public function execute(int $id): void
    {
        $tvShowResponse = $this->tmdbClient->getTvShow($id);
        if ($tvShowResponse->hasBeenSuccessful() === false) {
            return;
        }
        try {
            $tvShow = $this->tvShowRepository->find([
                'tmdb_id' => $tvShowResponse->getTmdbId(),
            ]);
        } catch (ModelNotFoundException $exception) {
            $this->logger->warning(__CLASS__.': Could not find TV show with TMDB id '.$tvShowResponse->getTmdbId());
            return;
        }

        $tvShow->popularity = $tvShowResponse->getPopularity();
        $this->tvShowRepository->save($tvShow);
    }

}
