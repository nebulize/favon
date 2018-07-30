<?php

namespace Favon\Television\Repositories;

use Favon\Television\Models\Episode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Favon\Application\Interfaces\RepositoryContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EpisodeRepository implements RepositoryContract
{
    /**
     * TVEpisode ORM.
     * @var Episode
     */
    protected $episode;

    /**
     * EpisodeRepository constructor.
     * @param Episode $episode
     */
    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    /**
     * Fetch a tv episode by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Episode
     */
    public function get(int $id, array $parameters = []): Episode
    {
        $query = $this->episode->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a tv episode by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Episode
     */
    public function find(array $parameters = []): Episode
    {
        $query = $this->episode->newQuery();

        // Filter by tv show
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }
        // Filter by season number
        if (isset($parameters['number'])) {
            $query = $query->where('number', $parameters['number']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all tv episodes, filtered by parameters.
     *
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->episode->newQuery();

        // Filter by TV Season
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }

        return $query->get();
    }

    /**
     * Create a new tv episode.
     *
     * @param array $attributes
     *
     * @return Episode
     */
    public function create(array $attributes): Episode
    {
        return $this->episode->newQuery()->create($attributes);
    }

    /**
     * Delete a tv episode.
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
     * Update an existing tv episode.
     *
     * @param Model $model
     * @param array $attributes
     */
    public function update(Model $model, array $attributes): void
    {
        $model->fill($attributes);
        $model->save();
    }

    /**
     * Get the episode count, filtered by parameters (e.g. the amount of episodes for a given tv season).
     *
     * @param array $parameters
     *
     * @return int
     */
    public function count(array $parameters = []): int
    {
        $query = $this->episode->newQuery();

        // Filter by tv season
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }

        return $query->count();
    }
}
