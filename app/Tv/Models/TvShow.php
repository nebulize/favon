<?php

namespace Favon\Tv\Models;

use Favon\Media\Models\Country;
use Favon\Media\Models\Genre;
use Favon\Media\Models\Language;
use Favon\Tv\Presenters\TvShowPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class TvShow extends Model
{
    use PresentableTrait;

    /**
     * Presenter class for formatting and presenting information.
     * @var string
     */
    protected $presenter = TvShowPresenter::class;

    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_shows';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'first_aired',
        'runtime',
        'summary',
        'plot',
        'poster',
        'banner',
        'homepage',
        'tv_status_id',
        'tv_rating_id',
        'tv_air_day_id',
        'air_time',
        'popularity',
        'imdb_score',
        'imdb_votes',
        'imdb_id',
        'tvdb_id',
        'tmdb_id',
    ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'first_aired'
    ];

    /**
     * Fields that should be casted to a certain type.
     * @var array
     */
    protected $casts = [
        'popularity' => 'float',
        'imdb_score' => 'float',
        'is_hidden' => 'boolean',
    ];

    /**
     * One-to-Many: one tv show can have many tv seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvSeasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TvSeason::class, 'tv_show_id');
    }

    /**
     * Many-to-Many: one tv show can have many genres.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_tv_show', 'tv_show_id', 'genre_id');
    }

    /**
     * Many-to-Many: one tv show can have many languages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_tv_show', 'tv_show_id', 'language_code');
    }

    /**
     * Many-to-Many: one tv show can have many countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_tv_show', 'tv_show_id', 'country_code');
    }

    /**
     * Many-to-Many: one tv show can have many networks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function networks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Network::class, 'network_tv_show', 'tv_show_id', 'network_id');
    }

    /**
     * One-to-Many (Many-to-Many with pivot table model): one tv show can have many user tv show entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvShow::class, 'tv_show_id');
    }

    /**
     * One-to-Many: one tv show belongs to one tv status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvStatus::class, 'tv_status_id');
    }

    /**
     * One-to-Many: one tv show belongs to one tv rating.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rating(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvRating::class, 'tv_rating_id');
    }

    /**
     * One-to-Many: one tv show belongs to one tv air day.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airDay(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvAirDay::class, 'tv_air_day_id');
    }
}
