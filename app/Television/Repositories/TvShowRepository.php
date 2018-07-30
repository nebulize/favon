<?php

namespace Favon\Television\Repositories;

use Favon\Application\Interfaces\RepositoryContract;
use Favon\Television\Models\TvShow;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TvShowRepository implements RepositoryContract
{
    /**
     * TvShow ORM.
     * @var TvShow
     */
    protected $tvShow;

    /**
     * TvShowRepository constructor.
     * @param TvShow $tvShow
     */
    public function __construct(TvShow $tvShow)
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
     * @return TvShow
     */
    public function get(int $id, array $parameters = []): TvShow
    {
        $query = $this->tvShow->newQuery()->where('id', $id);

        // Eager load relationship counts
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
     *
     * @throws ModelNotFoundException
     *
     * @return TvShow
     */
    public function find(array $parameters = []): TvShow
    {
        $query = $this->tvShow->newQuery();

        // Filter by imdb id
        if (isset($parameters['imdb_id'])) {
            $query = $query->where('imdb_id', $parameters['imdb_id']);
        }

        // Filter by tmdb id
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
        $query = $this->tvShow->newQuery();

        // Filter by date (created_at must be greater than given date)
        if (isset($parameters['created_at_gt'])) {
            $query = $query->where('created_at', '>=', $parameters['created_at_gt']);
        }

        // Filter by season (must have tv seasons belonging to a season newer than given season)
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
     *
     * @return TvShow
     */
    public function create(array $attributes): TvShow
    {
        return $this->tvShow->newQuery()->create($attributes);
    }

    /**
     * Delete an existing tv show.
     *
     * @param Model $model
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    /**
     * Update an existing tv show.
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
     * Store a new tv show instance in database.
     *
     * @param TvShow $tvShow
     */
    public function store(TvShow $tvShow): void
    {
        $tvShow->save();
    }

    /**
     * Add genres to tv show.
     *
     * @param TvShow $show
     * @param array $genres
     */
    public function syncGenres(TvShow $show, array $genres): void
    {
        $show->genres()->sync($genres);
    }

    /**
     * Add languages to tv show.
     *
     * @param TvShow $show
     * @param array $languages
     */
    public function syncLanguages(TvShow $show, array $languages): void
    {
        $show->languages()->sync($languages);
    }

    /**
     * Add countries to tv show.
     *
     * @param TvShow $show
     * @param array $countries
     */
    public function syncCountries(TvShow $show, array $countries): void
    {
        $show->countries()->sync($countries);
    }

    /**
     * Add networks to tv show.
     *
     * @param TvShow $show
     * @param array $networks
     */
    public function syncNetworks(TvShow $show, array $networks): void
    {
        $show->networks()->sync($networks);
    }

    /**
     * Save a tv show object to database.
     *
     * @param TvShow $tvShow
     */
    public function save(TvShow $tvShow): void
    {
        $tvShow->save();
    }

    /**
     * Get a random popular tv show.
     *
     * @return TvShow
     */
    public function getRandomPopularShow(): TvShow
    {
        return $this->tvShow->newQuery()
            ->select('tv.id', 'tv.banner')
            ->from(\DB::raw('(SELECT id, banner FROM tv_shows ORDER BY popularity DESC LIMIT 10) AS tv'))
            ->inRandomOrder()
            ->first();
    }

    /**
     * Delete a tv show from database by its TMDB id.
     *
     * @param int $id
     */
    public function deleteByTmdbId(int $id): void
    {
        $this->tvShow->newQuery()->where('tmdb_id', $id)->delete();
    }
}
