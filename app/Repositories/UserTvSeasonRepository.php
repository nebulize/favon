<?php

namespace App\Repositories;

use App\Models\UserTvSeason;

class UserTvSeasonRepository
{
    /**
     * @var UserTvSeason
     */
    protected $userTvSeason;

    /**
     * UserTvSeasonRepository constructor.
     * @param UserTvSeason $userTvSeason
     */
    public function __construct(UserTvSeason $userTvSeason)
    {
        $this->userTvSeason = $userTvSeason;
    }

    /**
     * Get a list of all user tv season entries.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = $this->userTvSeason->newQuery();
        if (isset($parameters['user_id'])) {
            $query = $query->where('user_tv_season.user_id', $parameters['user_id']);
        }
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('user_tv_season.tv_season_id', $parameters['tv_season_id']);
        }
        if (isset($parameters['status'])) {
            $query = $query->where('user_tv_season.status', $parameters['status']);
        }
        if (isset($parameters['tv_show_id'])) {
            $query = $query
                ->join('tv_seasons', 'user_tv_season.tv_season_id', '=', 'tv_seasons.id')
                ->where('tv_show_id', '=', $parameters['tv_show_id']);
        }

        return $query->get();
    }

    public function count(array $parameters = [])
    {
        $query = $this->userTvSeason->newQuery();
        if (isset($parameters['user_id'])) {
            $query = $query->where('user_id', $parameters['user_id']);
        }
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }
        if (isset($parameters['status'])) {
            $query = $query->where('status', $parameters['status']);
        }

        return $query->count();
    }
}
