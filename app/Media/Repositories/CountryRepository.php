<?php

namespace Favon\Media\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Media\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CountryRepository implements RepositoryContract
{
    /**
     * @var Country
     */
    private $country;

    /**
     * CountryRepository constructor.
     * @param Country $country
     */
    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * Fetch a country by its code.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Country
     */
    public function get(int $id, array $parameters = []): Country
    {
        $query = $this->country->newQuery()->where('code', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a country.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Country
     */
    public function find(array $parameters = []): Country
    {
        $query = $this->country->newQuery();

        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all countries.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->country->newQuery();

        return $query->get();
    }

    /**
     * Create a new country.
     *
     * @param array $attributes
     *
     * @return Country
     */
    public function create(array $attributes): Country
    {
        return $this->country->newQuery()->create($attributes);
    }

    /**
     * Delete an existing country.
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
     * Update an existing country.
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
