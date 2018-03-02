<?php

namespace App\Repositories;

use App\Models\Network;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NetworkRepository implements RepositoryContract
{
    /**
     * @var Network ORM
     */
    protected $network;

    /**
     * NetworkRepository constructor.
     * @param Network $network
     */
    public function __construct(Network $network)
    {
        $this->network = $network;
    }

    /**
     * Get a network by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Network
     */
    public function get(int $id, array $parameters = []): Network
    {
        $query = $this->network->where('id', $id);
        return $query->firstOrFail();
    }

    /**
     * Find a network by different parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Network
     */
    public function find(array $parameters = []): Network
    {
        /* @var Builder $query */
        $query = $this->network;

        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get an index of all networks.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->network;
        return $query->get();
    }

    /**
     * Create a new network and store it in database.
     *
     * @param array $attributes
     *
     * @return Network
     */
    public function create(array $attributes): Network
    {
        return $this->network->create($attributes);
    }

    /**
     * Delete an existing network from database.
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
     * Update an existing network in database.
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