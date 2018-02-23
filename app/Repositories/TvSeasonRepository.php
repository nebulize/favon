<?php

namespace App\Repositories;

use App\Models\Person;
use App\Models\TVSeason;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvSeasonRepository implements RepositoryContract
{
    /**
     * TVSeason ORM.
     * @var TVSeason
     */
    protected $tvSeason;

    /**
     * TvSeasonRepository constructor.
     * @param TVSeason $tvSeason
     */
    public function __construct(TVSeason $tvSeason)
    {
        $this->tvSeason = $tvSeason;
    }

    /**
     * Fetch a tv season by its ID.
     *
     * @param int $id
     * @param array $parameters
     * @return TVSeason
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = []) : TVSeason
    {
        $query = $this->tvSeason->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * Find a tv season by parameters.
     *
     * @param array $parameters
     * @return TVSeason
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = []) : TVSeason
    {
        $query = $this->tvSeason;
        // Filter by season
        if (isset($parameters['season_id'])) {
            $query = $query->where('season_id', $parameters['season_id']);
        }
        // Filter by tv show
        if (isset($parameters['tv_show_id'])) {
            $query = $query->where('tv_show_id', $parameters['tv_show_id']);
        }
        // Filter by season number
        if (isset($parameters['number'])) {
            $query = $query->where('number', $parameters['number']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all tv seasons, filtered by parameters.
     *
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []) : Collection
    {
        $query = $this->tvSeason;

        // Get seasonal index
        if (isset($parameters['seasonal']) && $parameters['seasonal'] === true) {
            $query = $query->join('tv_shows', 'tv_seasons.tv_show_id', '=', 'tv_shows.id');
            if (! isset($parameters['season_id'])) {
                throw new InvalidArgumentException('Requesting seasonal index, but no season_id specified.');
            }

            return $query
                ->where('tv_seasons.season_id', '=', $parameters['season_id'])
                ->where(function ($q) {
                    $q->where('tv_shows.imdb_votes', '>=', 2000)
                        ->orWhere('tv_shows.popularity', '>=', 20);
                })
                ->whereNotIn('tv_shows.id', function ($q) {
                    $q->select('tv_show_id')->from('genre_tv_show')->whereIn('genre_id', [3, 11, 13, 22]);
                })
                ->whereNotIn('tv_shows.id', function ($q) {
                    $q->select('tv_show_id')->from('country_tv_show')->where('country_code', 'IN');
                })
                ->orderBy('tv_shows.imdb_score', 'DESC')
                ->with('tvShow.genres')
                ->select(['tv_seasons.first_aired AS season_first_aired', 'tv_seasons.*', 'tv_shows.*'])
                ->get();
        }

        // Eager load relationships
        if (isset($parameters['with']) && \is_array($parameters['with'])) {
            foreach ($parameters['with'] as $relationship) {
                $query = $query->with($relationship);
            }
        }
        // Filter by season
        if (isset($parameters['season_id'])) {
            $query = $query->where('season_id', $parameters['season_id']);
        }

        return $query->get();
    }

    /**
     * Create a new tv season.
     *
     * @param array $attributes
     * @return TVSeason
     */
    public function create(array $attributes) : TVSeason
    {
        return $this->tvSeason->create($attributes);
    }

    /**
     * Delete an existing tv season.
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing tv season.
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

    public function addPerson(TVSeason $tvSeason, Person $person, array $attributes = []): void
    {
        $tvSeason->persons()->attach($person->id, $attributes);
    }
}
