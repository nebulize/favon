<?php

namespace App\Repositories;

use App\Models\TVShow;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvShowRepository implements RepositoryContract
{
    /**
     * TVShow ORM.
     * @var TVShow
     */
    protected $tvShow;

    /**
     * TvShowRepository constructor.
     * @param TVShow $tvShow
     */
    public function __construct(TVShow $tvShow)
    {
        $this->tvShow = $tvShow;
    }

    /**
     * Fetch a tv show by its ID.
     *
     * @param int $id
     * @param array $parameters
     *
     * @throws ModelNotFoundException
     *
     * @return TVShow
     */
    public function get(int $id, array $parameters = []): TVShow
    {
        $query = $this->tvShow->newQuery()->where('id', $id);
        if (isset($parameters['withCount']) && \is_array($parameters['withCount'])) {
            foreach ($parameters['withCount'] as $relationship) {
                $query = $query->withCount($relationship);
            }
        }

        return $query->firstOrFail();
    }

    /**
     * Find a tv show by parameters.
     *
     * @param array $parameters
     * @return TVShow
     * @throws ModelNotFoundException
     */
    public function find(array $parameters = []) : TVShow
    {
        $query = $this->tvShow;

        if (isset($parameters['imdb_id'])) {
            $query = $query->where('imdb_id', $parameters['imdb_id']);
        }

        if (isset($parameters['tmdb_id'])) {
            $query = $query->where('tmdb_id', $parameters['tmdb_id']);
        }

        return $query->firstOrFail();
    }

    /**
     * Get a list of all tv shows.
     *
     * @param array $parameters
     * @return Collection
     */
    public function index(array $parameters = []): Collection
    {
        /**
         * @var Builder
         */
        $query = $this->tvShow->newQuery();

        if (isset($parameters['created_at_gt'])) {
            $query = $query->where('created_at', '>=', $parameters['created_at_gt']);
        }

        if (isset($parameters['season_gt'])) {
            $query = $query
                ->join('tv_seasons', 'tv_seasons.tv_show_id', '=', 'tv_shows.id')
                ->join('seasons', 'tv_seasons.season_id', '=', 'seasons.id')
                ->where('seasons.start_date', '>=', $parameters['season_gt']->start_date)
                ->select(['tv_shows.*']);

            return $query->get();
        }

        // Order by given key
        if (isset($parameters['orderBy']) && \is_array($parameters['orderBy'])) {
            $query = $query->orderBy($parameters['orderBy'][0], $parameters['orderBy'][1]);
        }

        // Retrieve limited amount of rows
        if (isset($parameters['limit'])) {
            $query = $query->take($parameters['limit']);
        }

        return $query->get();
    }

    /**
     * Create and save a new tv show.
     *
     * @param array $attributes
     * @return TVShow
     */
    public function create(array $attributes) : TVShow
    {
        return $this->tvShow->create($attributes);
    }

    /**
     * Delete an existing tv show.
     *
     * @param Model $model
     */
    public function delete(Model $model) : void
    {
        $model->delete();
    }

    /**
     * Update an existing tv show.
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

    /**
     * Store a new TVShow instance in database.
     *
     * @param TVShow $tvShow
     */
    public function store(TVShow $tvShow) : void
    {
        $tvShow->save();
    }

    /**
     * Add genres to tv show.
     *
     * @param TVShow $show
     * @param array $genres
     */
    public function syncGenres(TVShow $show, array $genres) : void
    {
        $show->genres()->sync($genres);
    }

    /**
     * Add languages to tv show.
     *
     * @param TVShow $show
     * @param array $languages
     */
    public function syncLanguages(TVShow $show, array $languages) : void
    {
        $show->languages()->sync($languages);
    }

    /**
     * Add countries to tv show.
     *
     * @param TVShow $show
     * @param array $countries
     */
    public function syncCountries(TVShow $show, array $countries): void
    {
        $show->countries()->sync($countries);
    }

    /**
     * Add networks to tv show.
     *
     * @param TVShow $show
     * @param array $networks
     */
    public function syncNetworks(TVShow $show, array $networks): void
    {
        $show->networks()->sync($networks);
    }

    /**
     * Save a tv show object to database.
     *
     * @param TVShow $tvShow
     */
    public function save(TVShow $tvShow): void
    {
        $tvShow->save();
    }

    public function getRandomPopularShow()
    {
        return $this->tvShow
            ->select('tv.id', 'tv.banner')
            ->from(\DB::raw('(SELECT id, banner FROM tv_shows ORDER BY popularity DESC LIMIT 10) AS tv'))
            ->inRandomOrder()
            ->first();
    }
}
