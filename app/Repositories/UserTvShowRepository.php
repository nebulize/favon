<?php

namespace App\Repositories;

use App\Exceptions\InvalidArgumentException;
use App\Models\UserTvShow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTvShowRepository
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
     * Get a UserTvShow by user_id and tv_show_id (primary key).
     *
     * @param int $user_id
     * @param int $tv_show_id
     *
     * @throws ModelNotFoundException
     *
     * @return UserTvShow
     */
    public function get(int $user_id, int $tv_show_id): UserTvShow
    {
        return $this->userTvShow
            ->newQuery()
            ->where('user_id', $user_id)
            ->where('tv_show_id', $tv_show_id)
            ->firstOrFail();
    }

    public function find(array $parameters = [])
    {
        // TODO: Implement find() method.
    }

    public function index(array $parameters = [])
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

    public function create(array $attributes)
    {
        $this->userTvShow->create($attributes);
    }

    /**
     * Delete a user tv show entry by primary key.
     *
     * @param $user_id
     * @param $tv_show_id
     */
    public function delete($user_id, $tv_show_id): void
    {
        $this->userTvShow->newQuery()
            ->where('user_id', $user_id)
            ->where('tv_show_id', $tv_show_id)
            ->delete();
    }

    public function update(Model $model, array $attributes)
    {
        $model->fill($attributes);
        $model->save();
        return $model;
    }
}
