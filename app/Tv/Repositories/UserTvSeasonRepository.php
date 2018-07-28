<?php

namespace Favon\Repositories;

use Carbon\Carbon;
use Favon\Application\Interfaces\RepositoryContract;
use Favon\Auth\Models\User;
use Favon\Media\Enumerators\ListStatus;
use Favon\Tv\Models\TvSeason;
use Favon\Tv\Models\UserTvSeason;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTvSeasonRepository implements RepositoryContract
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
     * Get a user tv season entry by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return UserTvSeason
     */
    public function get(int $id, array $parameters = []): UserTvSeason
    {
        $query = $this->userTvSeason->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a user tv season entry by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return UserTvSeason
     */
    public function find(array $parameters = []): UserTvSeason
    {
        $query = $this->userTvSeason->newQuery();

        return $query->firstOrFail();
    }

    /**
     * Get a list of all user tv season entries, filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->userTvSeason->newQuery();

        // Filter by user id
        if (isset($parameters['user_id'])) {
            $query = $query->where('user_tv_season.user_id', $parameters['user_id']);
        }

        // Filter by tv season id
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('user_tv_season.tv_season_id', $parameters['tv_season_id']);
        }

        // Filter by list status
        if (isset($parameters['status'])) {
            $query = $query->where('user_tv_season.list_status_id', $parameters['status']);
        }

        // Filter by tv show id
        if (isset($parameters['tv_show_id'])) {
            $query = $query
                ->join('tv_seasons', 'user_tv_season.tv_season_id', '=', 'tv_seasons.id')
                ->where('tv_seasons.tv_show_id', '=', $parameters['tv_show_id']);
        }

        return $query->select('user_tv_season.*')->get();
    }

    /**
     * Create a new user tv season entry.
     *
     * @param array $attributes
     *
     * @return UserTvSeason
     */
    public function create(array $attributes): UserTvSeason
    {
        return $this->userTvSeason->newQuery()->create($attributes);
    }

    /**
     * Delete a user tv season entry from database.
     *
     * @param Model $model
     *
     * @throws \Exception
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Update an existing user tv season entry.
     *
     * @param Model $model
     * @param array $attributes
     */
    public function update(Model $model, array $attributes): void
    {
        $model->fill($attributes);
        $model->save();
    }

    /**
     * Get a count of user tv season entries, optionally filtered by parameters.
     *
     * @param array $parameters
     *
     * @return int
     */
    public function count(array $parameters = []): int
    {
        $query = $this->userTvSeason->newQuery();

        // Filter by user id
        if (isset($parameters['user_id'])) {
            $query = $query->where('user_id', $parameters['user_id']);
        }

        // Filter by tv season id
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }

        // Filter by list status
        if (isset($parameters['status'])) {
            $query = $query->where('list_status_id', $parameters['status']);
        }

        return $query->count();
    }

    /**
     * Add a tv season to a users' list.
     *
     * @param User $user
     * @param TvSeason $tvSeason
     * @param array $data
     */
    public function addTvSeasonToList(User $user, TvSeason $tvSeason, array $data): void
    {
        if ($data['list_status_id'] === ListStatus::STATUS_COMPLETED) {
            $data['progress'] = $tvSeason->episode_count;
            $data['completed_at'] = Carbon::now();
        }
        if ($data['score'] === 0) {
            $data['score'] = null;
        }
        $user->tvSeasons()->attach($tvSeason->id, $data);
    }

    public function updateTvSeasonListStatus(User $user, TvSeason $tvSeason, array $data): void
    {
        if ($data['list_status_id'] === ListStatus::STATUS_COMPLETED) {
            $data['progress'] = $tvSeason->episode_count;
            $data['completed_at'] = Carbon::now();
        }
        if ($data['score'] === 0) {
            $data['score'] = null;
        }
        $user->tvSeasons()->updateExistingPivot($tvSeason->id, $data);
    }

    /**
     * Remove a tv season from a users' list.
     *
     * @param User $user
     * @param TvSeason $tvSeason
     */
    public function removeTvSeasonFromList(User $user, TvSeason $tvSeason): void
    {
        $user->tvSeasons()->detach($tvSeason->id);
    }
}
