<?php

namespace Favon\Television\Models;

use Favon\Media\Models\Genre;
use Favon\Media\Models\Country;
use Favon\Media\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Favon\Television\Presenters\TvShowPresenter;

/**
 * Favon\Television\Models\TvShow.
 *
 * @property int $id
 * @property string|null $imdb_id
 * @property string $name
 * @property \Carbon\Carbon|null $first_aired
 * @property string|null $runtime
 * @property string|null $summary
 * @property string|null $plot
 * @property string|null $poster
 * @property string|null $banner
 * @property float $imdb_score
 * @property int $imdb_votes
 * @property string|null $air_time
 * @property float $popularity
 * @property int|null $tvdb_id
 * @property int $tmdb_id
 * @property string|null $homepage
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $is_hidden
 * @property int|null $production_status_id
 * @property int|null $tv_rating_id
 * @property int|null $tv_air_day_id
 * @property-read \Favon\Television\Models\AirDay|null $airDay
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Media\Models\Country[] $countries
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Media\Models\Genre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Media\Models\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\Network[] $networks
 * @property-read \Favon\Television\Models\Rating|null $rating
 * @property-read \Favon\Television\Models\ProductionStatus|null $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvSeason[] $tvSeasons
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\UserTvShow[] $userTvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereAirTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereFirstAired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereHomepage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereImdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereImdbScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereImdbVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereIsHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow wherePlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow wherePopularity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereProductionStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereRuntime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereTvAirDayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereTvRatingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereTvdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvShow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        'first_aired',
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
     * One-to-Many: one tv show belongs to one production status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductionStatus::class, 'production_status_id');
    }

    /**
     * One-to-Many: one tv show belongs to one tv rating.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rating(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Rating::class, 'tv_rating_id');
    }

    /**
     * One-to-Many: one tv show belongs to one tv air day.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function airDay(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AirDay::class, 'tv_air_day_id');
    }
}
