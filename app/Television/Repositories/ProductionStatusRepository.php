<?php

namespace Favon\Television\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Television\Models\ProductionStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductionStatusRepository implements RepositoryContract
{
    /**
     * @var ProductionStatus
     */
    protected $productionStatus;

    /**
     * ProductionStatusRepository constructor.
     * @param ProductionStatus $productionStatus
     */
    public function __construct(ProductionStatus $productionStatus)
    {
        $this->productionStatus = $productionStatus;
    }

    /**
     * Get a tv status by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return ProductionStatus
     */
    public function get(int $id, array $parameters = []): ProductionStatus
    {
        $query = $this->productionStatus->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a tv status by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return ProductionStatus
     */
    public function find(array $parameters = []): ProductionStatus
    {
        $query = $this->productionStatus->newQuery();

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
        $query = $this->productionStatus->newQuery();

        return $query->get();
    }

    /**
     * Create a new tv status.
     *
     * @param array $attributes
     *
     * @return ProductionStatus
     */
    public function create(array $attributes): ProductionStatus
    {
        return $this->productionStatus->newQuery()->create($attributes);
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
