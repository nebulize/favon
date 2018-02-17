<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LanguageRepository implements RepositoryContract
{
    /**
     * Language ORM.
     * @var Language
     */
    protected $language;

    /**
     * LanguageRepository constructor.
     * @param Language $language
     */
    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    /**
     * Fetch a language by its ID.
     *
     * @param int $id
     * @param array $parameters
     * @return Language
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = []) : Language
    {
        $query = $this->language->where('code', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a language by parameters.
     *
     * @param $parameters
     * @return Language
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = []) : Language
    {
        $query = $this->language;
        // Filter by name
        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all languages.
     *
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []) : Collection
    {
        $query = $this->language;

        return $query->get();
    }

    /**
     * Create a new language.
     *
     * @param $attributes
     * @return Language
     */
    public function create($attributes) : Language
    {
        return $this->language->create($attributes);
    }

    /**
     * Delete an existing language.
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing language.
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
