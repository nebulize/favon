<?php

namespace Favon\Media\Repositories;

use Favon\Media\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Favon\Application\Interfaces\RepositoryContract;
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
     *
     * @throws ModelNotFoundException
     *
     * @return Language
     */
    public function get(int $id, array $parameters = []): Language
    {
        $query = $this->language->newQuery()->where('code', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a language by parameters.
     *
     * @param $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Language
     */
    public function find(array $parameters = []): Language
    {
        $query = $this->language->newQuery();

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
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->language->newQuery();

        return $query->get();
    }

    /**
     * Create a new language.
     *
     * @param $attributes
     *
     * @return Language
     */
    public function create($attributes): Language
    {
        return $this->language->newQuery()->create($attributes);
    }

    /**
     * Delete an existing language.
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
     * Update an existing language.
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
