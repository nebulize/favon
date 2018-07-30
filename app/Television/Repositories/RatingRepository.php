<?php

namespace Favon\Television\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Television\Models\Rating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RatingRepository implements RepositoryContract
{
    /**
     * @var Rating
     */
    protected $rating;

    /**
     * RatingRepository constructor.
     * @param Rating $rating
     */
    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * Fetch a rating by its id.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Rating
     */
    public function get(int $id, array $parameters = []): Rating
    {
        $query = $this->rating->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a rating by supplied parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Rating
     */
    public function find(array $parameters = []): Rating
    {
        $query = $this->rating->newQuery();

        if (isset($parameters['name'])) {
            $query = $query->where('name', $parameters['name']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all ratings, optionally filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->rating->newQuery();

        return $query->get();
    }

    /**
     * Create a new rating instance and store it in database.
     *
     * @param array $attributes
     *
     * @return Rating
     */
    public function create(array $attributes): Rating
    {
        return $this->rating->newQuery()->create($attributes);
    }

    /**
     * Delete a rating instance from database.
     *
     * @param Model $model
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Update an existing rating instance in database.
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
