<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VideoRepository implements RepositoryContract
{
    /**
     * Video ORM.
     * @var Video
     */
    protected $video;

    /**
     * VideoRepository constructor.
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Get a video by its ID.
     *
     * @param int $id
     * @param array $parameters
     * @return Video
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = []) : Video
    {
        $query = $this->video->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a video by parameters.
     *
     * @param array $parameters
     * @return Video
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = []) : Video
    {
        $query = $this->video;

        return $query->firstOrFail();
    }

    /**
     * Get a list of all videos, filtered by parameters.
     *
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []) : Collection
    {
        $query = $this->video;
        // Filter by tv season
        if (isset($parameters['tv_season_id'])) {
            $query = $query->where('tv_season_id', $parameters['tv_season_id']);
        }

        return $query->get();
    }

    /**
     * Create a new Video.
     *
     * @param array $attributes
     * @return Video
     */
    public function create(array $attributes) : Video
    {
        return $this->video->create($attributes);
    }

    /**
     * Delete a Video.
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing Video.
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
