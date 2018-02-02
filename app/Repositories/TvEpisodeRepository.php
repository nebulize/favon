<?php

namespace App\Repositories;

use App\Models\TVEpisode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvEpisodeRepository implements RepositoryContract
{
    /**
     * TVEpisode ORM
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
     * Fetch a tv episode by its ID
     *
     * @param int $id
     * @param array $parameters
     * @return TVEpisode
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = array()) : TVEpisode
    {
        $query = $this->tvEpisode->where('id', $id);
        return $query->firstOrFail();
    }

    /**
     * Find a tv episode by parameters
     *
     * @param array $parameters
     * @return TVEpisode
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = array()) : TVEpisode
    {
        $query = $this->tvEpisode;
        return $query->firstOrFail();
    }

    /**
     * Get a list of all tv episodes, filtered by parameters
     *
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = array()) : Collection
    {
        $query = $this->tvEpisode;
        // Filter by TV Season
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }
        return $query->get();
    }

    /**
     * Create a new tv episode
     *
     * @param array $attributes
     * @return TVEpisode
     */
    public function create(array $attributes) : TVEpisode
    {
        return $this->tvEpisode->create($attributes);
    }

    /**
     * Delete a tv episode
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing tv episode
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