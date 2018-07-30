<?php

namespace Favon\Television\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Television\Models\AirDay;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AirDayRepository implements RepositoryContract
{
    /**
     * @var AirDay
     */
    protected $airDay;

    /**
     * AirDayRepository constructor.
     * @param AirDay $airDay
     */
    public function __construct(AirDay $airDay)
    {
        $this->airDay = $airDay;
    }

    /**
     * Fetch an air day by its id.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return AirDay
     */
    public function get(int $id, array $parameters = []): AirDay
    {
        $query = $this->airDay->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find an air day by supplied parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return AirDay
     */
    public function find(array $parameters = []): AirDay
    {
        $query = $this->airDay->newQuery();

        // Filter by name
        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all air days, optionally filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->airDay->newQuery();

        return $query->get();
    }

    /**
     * Create a new air day instance, and store it in database.
     *
     * @param array $attributes
     *
     * @return AirDay
     */
    public function create(array $attributes): AirDay
    {
        return $this->airDay->newQuery()->create($attributes);
    }

    /**
     * Delete an air day instance from database.
     *
     * @param Model $model
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Update an existing air day instance in database.
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
