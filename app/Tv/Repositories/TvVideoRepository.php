<?php

namespace Favon\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Tv\Models\TvVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvVideoRepository implements RepositoryContract
{
    /**
     * Video ORM.
     * @var TvVideo
     */
    protected $video;

    /**
     * VideoRepository constructor.
     * @param TvVideo $video
     */
    public function __construct(TvVideo $video)
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
     * @return TvVideo
     */
    public function get(int $id, array $parameters = []): TvVideo
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
     * @return TvVideo
     */
    public function find(array $parameters = []): TvVideo
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
     * @return TvVideo
     */
    public function create(array $attributes): TvVideo
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
