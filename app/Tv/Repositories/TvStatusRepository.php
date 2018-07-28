<?php

namespace Favon\Tv\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Tv\Models\TvStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvStatusRepository implements RepositoryContract
{
    /**
     * @var TvStatus
     */
    protected $tvStatus;

    /**
     * TvStatusRepository constructor.
     * @param TvStatus $tvStatus
     */
    public function __construct(TvStatus $tvStatus)
    {
        $this->tvStatus = $tvStatus;
    }

    /**
     * Get a tv status by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return TvStatus
     */
    public function get(int $id, array $parameters = []): TvStatus
    {
        $query = $this->tvStatus->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a tv status by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return TvStatus
     */
    public function find(array $parameters = []): TvStatus
    {
        $query = $this->tvStatus->newQuery();

        return $query->firstOrFail();
    }

    /**
     * Get a list of all tv statuses, filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->tvStatus->newQuery();

        return $query->get();
    }

    /**
     * Create a new tv status.
     *
     * @param array $attributes
     *
     * @return TvStatus
     */
    public function create(array $attributes): TvStatus
    {
        return $this->tvStatus->create($attributes);
    }

    /**
     * Delete a tv season from database.
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
     * Update an existing tv season.
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
