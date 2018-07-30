<?php

namespace Favon\Television\Services\Api;

use Favon\Application\Exceptions\NoAPIResultsFoundException;
use Favon\Application\Exceptions\TvSeasonWasDeletedException;
use Favon\Application\Exceptions\TvShowWasDeletedException;
use Favon\Television\Http\Responses\TMDB\Models\RSeason;
use Favon\Television\Models\TvSeason;
use Favon\Television\Models\TvShow;
use Favon\Television\Repositories\TvShowRepository;
use Intervention\Image\Facades\Image;
use Psr\Log\LoggerInterface;

class ApiService
{
    protected $tvShowFetchingService;
    protected $tvSeasonFetchingService;
    protected $tvSeasonVideoFetchingService;
    protected $tvSeasonCreditFetchingService;
    protected $tvShowRepository;
    protected $logger;

    public function __construct(
        TvShowFetchingService $tvShowFetchingService,
        TvSeasonFetchingService $tvSeasonFetchingService,
        TvSeasonVideoFetchingService $tvSeasonVideoFetchingService,
        TvSeasonCreditFetchingService $tvSeasonCreditFetchingService,
        TvShowRepository $tvShowRepository,
        LoggerInterface $logger)
    {
        $this->tvShowFetchingService = $tvShowFetchingService;
        $this->tvSeasonFetchingService = $tvSeasonFetchingService;
        $this->tvSeasonVideoFetchingService = $tvSeasonVideoFetchingService;
        $this->tvSeasonCreditFetchingService = $tvSeasonCreditFetchingService;
        $this->tvShowRepository = $tvShowRepository;
        $this->logger = $logger;
    }

    /**
     * Fetch a single tv show and all its data.
     *
     * @param int $id
     */
    public function fetchTvShow(int $id): void
    {
        try {
            $tvSeasons = $this->tvShowFetchingService->fetch($id);
        } catch (NoAPIResultsFoundException $exception) {
            $this->logger->warning($exception->getMessage());
            return;
        }
        $tvShow = $this->tvShowRepository->find(['tmdb_id' => $id]);
        $this->fetchTvSeasons($tvShow, $tvSeasons);
    }

    public function updateTvShow(int $id): void
    {
        try {
            $tvSeasons = $this->tvShowFetchingService->update($id);
        } catch (NoAPIResultsFoundException $exception) {
            $this->logger->warning($exception->getMessage());
            return;
        } catch (TvShowWasDeletedException $exception) {
            // Show was deleted on TMDB, delete it from our database as well.
            $this->tvShowRepository->deleteByTmdbId($id);
            $this->logger->warning($exception->getMessage());
            return;
        }
        $tvShow = $this->tvShowRepository->find(['tmdb_id' => $id]);
        $this->updateTvSeasons($tvShow, $tvSeasons);
    }

    /**
     * Fetch all tv seasons belonging to a tv show.
     *
     * @param TvShow $tvShow
     * @param RSeason[] $tvSeasons
     */
    protected function fetchTvSeasons(TvShow $tvShow, array $tvSeasons): void
    {
        foreach ($tvSeasons as $season) {
            try {
                $tvSeason = $this->tvSeasonFetchingService->fetch($tvShow, $season->getNumber());
            } catch (NoAPIResultsFoundException $exception) {
                $this->logger->warning($exception->getMessage());
                continue;
            }
            $this->fetchVideos($tvShow->tmdb_id, $tvSeason);
            $this->fetchCredits($tvShow->tmdb_id, $tvSeason);
        }
    }

    /**
     * Fetch tv season videos.
     *
     * @param int $id
     * @param TvSeason $tvSeason
     */
    protected function fetchVideos(int $id, TvSeason $tvSeason): void
    {
        try {
            $this->tvSeasonVideoFetchingService->execute($id, $tvSeason);
        } catch (NoAPIResultsFoundException $exception) {
            $this->logger->warning($exception->getMessage());
        }
    }

    /**
     * Fetch tv season credits.
     *
     * @param int $id
     * @param TvSeason $tvSeason
     */
    protected function fetchCredits(int $id, TvSeason $tvSeason): void
    {
        try {
            $this->tvSeasonCreditFetchingService->execute($id, $tvSeason);
        } catch (NoAPIResultsFoundException $exception) {
            $this->logger->warning($exception->getMessage());
        }
    }

    /**
     * Update all tv seasons belonging to a tv show.
     *
     * @param TvShow $tvShow
     * @param RSeason[] $tvSeasons
     */
    protected function updateTvSeasons(TvShow $tvShow, array $tvSeasons): void
    {
        foreach ($tvSeasons as $season) {
            try {
                $tvSeason = $this->tvSeasonFetchingService->update($tvShow, $season->getNumber());
            } catch (NoAPIResultsFoundException $exception) {
                $this->logger->warning($exception->getMessage());
                continue;
            } catch (TvSeasonWasDeletedException $exception) {
                $this->logger->warning($exception->getMessage());
                continue;
            }
            $tvSeason->videos()->delete();
            $this->fetchVideos($tvShow->tmdb_id, $tvSeason);
            $tvSeason->persons()->detach();
            $this->fetchCredits($tvShow->tmdb_id, $tvSeason);
        }
    }

    /**
     * Fetch images from TMDB and store locally.
     *
     * @param string $type
     * @param string $path
     */
    public function fetchImages(string $type, string $path): void
    {
        $sizes = config('favon.'.$type.'_sizes');
        $base_path = config('favon.image_base_path');
        foreach ($sizes as $size) {
            $this->fetchImage($base_path.'/'.$size.$path, public_path('images/'.$type.'/'.$size.'/'.basename($path)), 0);
        }
    }

    /**
     * Fetch a single image, with a maximum of 10 tries.
     *
     * @param $from
     * @param $to
     * @param $tries
     *
     * @return bool
     */
    protected function fetchImage($from, $to, $tries): bool
    {
        if ($tries > 10) {
            return false;
        }

        try {
            Image::make($from)->save($to);
        } catch (\Exception $exception) {
            return $this->fetchImage($from, $to, $tries + 1);
        }

        return true;
    }

}
