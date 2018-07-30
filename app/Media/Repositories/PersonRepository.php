<?php

namespace Favon\Media\Repositories;

use Favon\Media\Models\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Favon\Application\Interfaces\RepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonRepository implements RepositoryContract
{
    /**
     * Person ORM.
     * @var Person
     */
    protected $person;

    /**
     * PersonRepository constructor.
     * @param Person $person
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Fetch a person by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Person
     */
    public function get(int $id, array $parameters = []): Person
    {
        $query = $this->person->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a person by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Person
     */
    public function find(array $parameters = []): Person
    {
        $query = $this->person->newQuery();

        // Filter by tmdb id
        if ($parameters['tmdb_id']) {
            $query = $query->where('tmdb_id', $parameters['tmdb_id']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all persons, filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->person->newQuery();

        return $query->get();
    }

    /**
     * Create and save a new person.
     *
     * @param array $attributes
     *
     * @return Person
     */
    public function create(array $attributes): Person
    {
        return $this->person->newQuery()->create($attributes);
    }

    /**
     * Delete an existing person.
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
     * Update an existing person.
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
