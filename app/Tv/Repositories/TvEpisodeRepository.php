<?php

namespace Favon\Tv\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Tv\Models\TVEpisode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvEpisodeRepository implements RepositoryContract
{
    /**
     * TVEpisode ORM.
     * @var TVEpisode
     */
    protected $tvEpisode;

    /**
     * TvEpisodeRepository constructor.
     * @param TVEpisode $tvEpisode
     */
    public function __construct(TVEpisode $tvEpisode)
    {
        $this->tvEpisode = $tvEpisode;
    }

    /**
     * Fetch a tv episode by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return TVEpisode
     */
    public function get(int $id, array $parameters = []): TVEpisode
    {
        $query = $this->tvEpisode->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a tv episode by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return TVEpisode
     */
    public function find(array $parameters = []): TVEpisode
    {
        $query = $this->tvEpisode->newQuery();

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
        $query = $this->tvEpisode->newQuery();

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
     * @return TVEpisode
     */
    public function create(array $attributes): TVEpisode
    {
        return $this->tvEpisode->newQuery()->create($attributes);
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
     *
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
        $query = $this->tvEpisode->newQuery();

        // Filter by tv season
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }

        return $query->count();
    }
}
