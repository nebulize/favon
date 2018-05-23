<?php

namespace App\Repositories;

use App\Models\Genre;
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
     * @return Genre
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = []) : Genre
    {
        $query = $this->genre->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a genre by parameters.
     *
     * @param $parameters
     * @return Genre
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = []) : Genre
    {
        $query = $this->genre;
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []) : Collection
    {
        $query = $this->genre->newQuery();

        if (isset($parameters['orderBy']) && \is_array($parameters['orderBy'])) {
            $query = $query->orderBy($parameters['orderBy'][0], $parameters['orderBy'][1]);
        }

        return $query->get();
    }

    /**
     * Create a new genre.
     *
     * @param $attributes
     * @return Genre
     */
    public function create($attributes) : Genre
    {
        return $this->genre->create($attributes);
    }

    /**
     * Delete a genre.
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing genre.
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
