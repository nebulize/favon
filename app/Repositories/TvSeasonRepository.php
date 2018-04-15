<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Person;
use App\Models\TVSeason;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

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
        $query = $this->tvSeason->newQuery();
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

        // Order by given key
        if (isset($parameters['orderBy']) && \is_array($parameters['orderBy'])) {
            $query = $query->orderBy($parameters['orderBy'][0], $parameters['orderBy'][1]);
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
        $query = $this->tvSeason->newQuery();

        // Filter by season
        if (isset($parameters['season_id'])) {
            $query = $query->where('tv_seasons.season_id', $parameters['season_id']);
        }

        // Get seasonal index
        if (isset($parameters['seasonal']) && $parameters['seasonal'] === true) {
            $query = $query
                ->join('tv_shows', 'tv_seasons.tv_show_id', '=', 'tv_shows.id')
                ->where('tv_shows.is_hidden', false);

            // Filter out sequels or not
            if (isset($parameters['sequels']) && $parameters['sequels'] === true) {
                $query = $query->where('tv_seasons.number', '>', 0);
            } else {
                $query = $query->where('tv_seasons.number', '=', 1);
            }

            // Additional filtering so that we get reasonable results
            $query = $query
                ->where(function (EloquentBuilder $q) {
                    $q->where('tv_shows.imdb_votes', '>=', 1000);
                    $q->orWhere('tv_shows.popularity', '>=', 15);
                    // Also include shows from a few selected networks by default (Netflix, HBO, Amazon, Hulu, SyFy, Showtime, FX, The CW, AMC, Starz, BBC America)
                    $q->orWhereIn('tv_shows.id', function (QueryBuilder $q2) {
                        $q2->select('tv_show_id')->from('network_tv_show')->whereIn('network_id', [113, 25, 92, 39, 131, 503, 746, 27, 30, 303, 411]);
                    });
                });

            // Only fetch entries from the top 6 languages
            $query = $query->whereIn('tv_shows.id', function (QueryBuilder $q) {
                $q->select('tv_show_id')->from('language_tv_show')->whereIn('language_code', ['en', 'ja', 'de', 'fr', 'ko', 'es']);
            });

            $query = $query
                ->with('tvShow.genres')
                ->with('tvShow.languages')
//                ->with('tvShow.networks')
                ->orderBy('tv_shows.popularity', 'DESC')
                ->select(['tv_seasons.*']);
//                ->withCount('tvEpisodes');

            return $query->get();
        }

        // Eager load relationships
        if (isset($parameters['with']) && \is_array($parameters['with'])) {
            foreach ($parameters['with'] as $relationship) {
                $query = $query->with($relationship);
            }
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
