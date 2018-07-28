<?php

namespace Favon\Auth\Repositories;

use Carbon\Carbon;
use Favon\Application\Interfaces\RepositoryContract;
use Favon\Auth\Models\User;
use Favon\Media\Enumerators\ListStatus;
use Favon\Tv\Models\TvSeason;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements RepositoryContract
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
     * Fetch a user by his id.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return User
     */
    public function get(int $id, array $parameters = []): User
    {
        $query = $this->user->newQuery()->where('code', $id);

        return $query->firstOrFail();
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

        // Filter by email token
        if (isset($parameters['email_token'])) {
            $query = $query->where('email_token', $parameters['email_token']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all users, filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->user->newQuery();

        return $query->get();
    }

    /**
     * Create and save a new user.
     *
     * @param array $attributes
     *
     * @return User
     */
    public function create(array $attributes): User
    {
        return $this->user->newQuery()->create($attributes);
    }

    /**
     * Delete an existing user.
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
     * Update an existing user.
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
