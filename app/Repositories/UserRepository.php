<?php

namespace App\Repositories;

use App\Models\User;

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
