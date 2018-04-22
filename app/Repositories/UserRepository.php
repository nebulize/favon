<?php

namespace App\Repositories;

use App\Models\User;
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
     *
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
}
