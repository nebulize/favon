<?php

namespace App\Repositories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PersonRepository implements RepositoryContract
{
    /**
     * Person ORM
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
     * Fetch a person by its ID
     *
     * @param int $id
     * @param array $parameters
     * @return Person
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = array()) : Person
    {
        $query = $this->person->where('id', $id);
        return $query->firstOrFail();
    }

    /**
     * Find a person by parameters
     *
     * @param array $parameters
     * @return Person
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = array()) : Person
    {
        $query = $this->person;
        return $query->firstOrFail();
    }

    /**
     * Get a list of all persons, filtered by parameters
     *
     * @param array $parameters
     * @return Collection
     */
    public function index(array $parameters = array()) : Collection
    {
        $query = $this->person;
        return $query->get();
    }

    /**
     * Create and save a new person
     *
     * @param array $attributes
     * @return Person
     */
    public function create(array $attributes) : Person
    {
        return $this->person->create($attributes);
    }

    /**
     * Delete an existing person
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing person
     *
     * @param Model $model
     * @param array $attributes
     * @return Model
     */
    public function update(Model $model, array $attributes) : Model
    {
        $model->fill($attributes);
        $model->save();
        return $model;
    }
}