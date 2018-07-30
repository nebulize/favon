<?php

namespace Favon\Television\Repositories;

use Favon\Television\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Favon\Application\Interfaces\RepositoryContract;
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
     *
     * @throws ModelNotFoundException
     *
     * @return Video
     */
    public function get(int $id, array $parameters = []): Video
    {
        $query = $this->video->newQuery()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a video by parameters.
     *
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return Video
     */
    public function find(array $parameters = []): Video
    {
        $query = $this->video->newQuery();

        return $query->firstOrFail();
    }

    /**
     * Get a list of all videos, filtered by parameters.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        $query = $this->video->newQuery();

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
     *
     * @return Video
     */
    public function create(array $attributes): Video
    {
        return $this->video->newQuery()->create($attributes);
    }

    /**
     * Delete a video from database.
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
     * Update an existing Video.
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
     * Delete all videos for a given tv season.
     *
     * @param int $tv_season_id
     */
    public function deleteForSeason(int $tv_season_id): void
    {
        $this->video->newQuery()->where('tv_season_id', $tv_season_id)->delete();
    }
}
