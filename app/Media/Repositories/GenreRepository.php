<?php

namespace Favon\Media\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Media\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GenreRepository implements RepositoryContract
{
    /**
     * Genre ORM.
     * @var Genre
     */
    protected $genre;

    /**
     * GenreRepository constructor.
     * @param Genre $genre
     */
    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }

    /**
     * Fetch a genre by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Genre
     */
    public function get(int $id, array $parameters = []): Genre
    {
        $query = $this->genre->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a genre by parameters.
     *
     * @param $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Genre
     */
    public function find(array $parameters = []): Genre
    {
        $query = $this->genre->newQuery();

        // Filter by name
        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all genres.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->genre->newQuery();

        // Order query results
        if (isset($parameters['orderBy']) && \is_array($parameters['orderBy'])) {
            $query = $query->orderBy($parameters['orderBy'][0], $parameters['orderBy'][1]);
        }

        return $query->get();
    }

    /**
     * Create a new genre.
     *
     * @param $attributes
     *
     * @return Genre
     */
    public function create($attributes): Genre
    {
        return $this->genre->newQuery()->create($attributes);
    }

    /**
     * Delete a genre.
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
     * Update an existing genre.
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
