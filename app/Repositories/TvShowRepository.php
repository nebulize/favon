<?php

namespace App\Repositories;

use App\Models\TVShow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
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
     * @return TVShow
     * @throws ModelNotFoundException
     */
    public function get(int $id, array $parameters = []) : TVShow
    {
        $query = $this->tvShow->where('id', $id);

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

        return $query->firstOrFail();
    }

    /**
     * Get a list of all tv shows.
     *
     * @param array $parameters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(array $parameters = []) : Collection
    {
        $query = $this->tvShow;

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
     * Save a tv show object to database.
     *
     * @param TVShow $tvShow
     */
    public function save(TVShow $tvShow): void
    {
        $tvShow->save();
    }
}
