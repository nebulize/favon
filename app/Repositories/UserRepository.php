<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TVSeason;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $id
     *
     * @throws ModelNotFoundException
     *
     * @return User
     */
    public function get($id): User
    {
        return $this->user->findOrFail($id);
    }

    /**
     * Find a user by supplied parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return User
     */
    public function find(array $parameters = []): User
    {
        $query = $this->user->newQuery();
        if (isset($parameters['email_token'])) {
            $query = $query->where('email_token', $parameters['email_token']);
        }

        return $query->firstOrFail();
    }

    /**
     * Create and store a new user instance in database.
     *
     * @param $attributes
     * @return User
     */
    public function create($attributes): User
    {
        return $this->user->create($attributes);
    }

    /**
     * Add a tv season to a users' list.
     *
     * @param User $user
     * @param TVSeason $tvSeason
     * @param array $data
     */
    public function addTvSeasonToList(User $user, TVSeason $tvSeason, array $data): void
    {
        if ($data['status'] === User::STATUS_COMPLETED) {
            $data['progress'] = $tvSeason->episode_count;
        }
        if ($data['score'] === 0) {
            $data['score'] = null;
        }
        $user->tvSeasons()->attach($tvSeason->id, $data);
    }

    public function updateTvSeasonListStatus(User $user, TVSeason $tvSeason, array $data): void
    {
        if ($data['status'] === User::STATUS_COMPLETED) {
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
     * @param TVSeason $tvSeason
     */
    public function removeTvSeasonFromList(User $user, TVSeason $tvSeason): void
    {
        $user->tvSeasons()->detach($tvSeason->id);
    }
}
