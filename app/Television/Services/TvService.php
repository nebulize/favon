<?php

namespace Favon\Television\Services;

use Favon\Television\Repositories\TvShowRepository;
use Favon\Television\Repositories\TvSeasonRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvService
{
    /**
     * @var TvShowRepository
     */
    protected $tvShowRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * TvService constructor.
     * @param TvShowRepository $tvShowRepository
     * @param TvSeasonRepository $tvSeasonRepository
     */
    public function __construct(TvShowRepository $tvShowRepository, TvSeasonRepository $tvSeasonRepository)
    {
        $this->tvShowRepository = $tvShowRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
    }

    /**
     * Get the banner of a randomly selected, currently popular show.
     *
     * @return string|null
     */
    public function getBanner(): ?string
    {
        $selected = $this->tvShowRepository->getRandomPopularShow();
        try {
            $latestSeason = $this->tvSeasonRepository->find([
                'tv_show_id' => $selected->id,
                'orderBy' => ['number', 'DESC'],
            ]);
            $banner = $latestSeason->banner ?? $selected->banner;
        } catch (ModelNotFoundException $exception) {
            $banner = $selected->banner;
        }

        return $banner;
    }
}
