<?php

namespace Favon\Television\Services\Api;

use Favon\Television\Models\TvSeason;
use Favon\Television\Http\Clients\TmdbTvClient;
use Favon\Television\Repositories\VideoRepository;
use Favon\Application\Exceptions\NoAPIResultsFoundException;

class TvSeasonVideoFetchingService
{
    /**
     * @var TmdbTvClient
     */
    protected $tmdbClient;

    /**
     * @var VideoRepository
     */
    protected $videoRepository;

    /**
     * TvSeasonVideoFetchingService constructor.
     * @param TmdbTvClient $tmdbTvClient
     * @param VideoRepository $videoRepository
     */
    public function __construct(TmdbTvClient $tmdbTvClient, VideoRepository $videoRepository)
    {
        $this->tmdbClient = $tmdbTvClient;
        $this->videoRepository = $videoRepository;
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
            $this->videoRepository->create([
                'name' => $result->getName(),
                'key' => $result->getKey(),
                'type' => $result->getType(),
                'tv_season_id' => $tvSeason->id,
            ]);
        }
    }
}
