<?php

namespace Favon\Tv\Services\Api;

use Favon\Application\Exceptions\NoAPIResultsFoundException;
use Favon\Repositories\TvVideoRepository;
use Favon\Tv\Http\Clients\TmdbTvClient;
use Favon\Tv\Models\TvSeason;

class TvSeasonVideoFetchingService
{
    /**
     * @var TmdbTvClient
     */
    protected $tmdbClient;

    /**
     * @var TvVideoRepository
     */
    protected $tvVideoRepository;

    /**
     * TvSeasonVideoFetchingService constructor.
     * @param TmdbTvClient $tmdbTvClient
     * @param TvVideoRepository $tvVideoRepository
     */
    public function __construct(TmdbTvClient $tmdbTvClient, TvVideoRepository $tvVideoRepository)
    {
        $this->tmdbClient = $tmdbTvClient;
        $this->tvVideoRepository = $tvVideoRepository;
    }

    /**
     * Fetch all videos for a given tv season.
     *
     * @param int $id
     * @param TvSeason $tvSeason
     *
     * @throws NoAPIResultsFoundException
     */
    public function execute(int $id, TvSeason $tvSeason): void
    {
        $tvSeasonVideosResponse = $this->tmdbClient->getTvSeasonVideos($id, $tvSeason->number);

        if ($tvSeasonVideosResponse->hasBeenSuccessful() === false) {
            throw new NoAPIResultsFoundException(__CLASS__.': No results found for id '.$id.' S#'.$tvSeason->number);
        }

        foreach ($tvSeasonVideosResponse->getResults() as $result) {
            $this->tvVideoRepository->create([
                'name' => $result->getName(),
                'key' => $result->getKey(),
                'type' => $result->getType(),
                'tv_season_id' => $tvSeason->id,
            ]);
        }
    }

}
