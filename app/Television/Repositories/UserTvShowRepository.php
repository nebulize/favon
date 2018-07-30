<?php

namespace Favon\Television\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Television\Models\UserTvShow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserTvShowRepository implements RepositoryContract
{
    /**
     * @var UserTvShow
     */
    protected $userTvShow;

    /**
     * UserTvShowRepository constructor.
     * @param UserTvShow $userTvShow
     */
    public function __construct(UserTvShow $userTvShow)
    {
        $this->userTvShow = $userTvShow;
    }

    /**
     * Fetch a user tv show entry by its id.
     *
     * @param int $id
     * @param array $parameters
     *
     * @return UserTvShow
     */
    public function get(int $id, array $parameters = []): UserTvShow
    {
        $query = $this->userTvShow->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a user tv show entry by supplied parameters.
     *
     * @param array $parameters
     *
     * @return UserTvShow
     */
    public function find(array $parameters = []): UserTvShow
    {
        $query = $this->userTvShow->newQuery();

        // Filter by user id
        if (isset($parameters['user_id'])) {
            $query = $query->where('user_id', $parameters['user_id']);
        }

        // Filter by tv show id
        if (isset($parameters['tv_show_id'])) {
            $query = $query->where('tv_show_id', $parameters['tv_show_id']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all user tv show entries, optionally filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->userTvShow->newQuery();
        if (isset($parameters['user_id'])) {
            $query = $query->where('user_id', $parameters['user_id']);
        }
        if (isset($parameters['tv_show_id'])) {
            $query = $query->where('tv_show_id', $parameters['tv_show_id']);
        }
        return $query->get();
    }

    /**
     * Create a new user tv show entry and store it in database.
     *
     * @param array $attributes
     *
     * @return UserTvShow
     */
    public function create(array $attributes): UserTvShow
    {
        return $this->userTvShow->newQuery()->create($attributes);
    }

    /**
     * Delete a user tv show entry from database.
     *
     * @param Model $model
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Update an existing user tv show entry in database.
     *
     * @param Model $model
     * @param array $attributes
     */
    public function update(Model $model, array $attributes): void
    {
        $model->fill($attributes);
        $model->save();
    }
}
