<?php

namespace App\Models;

use App\Presenters\TvShowPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\TVShow.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVEpisode[] $episodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Genre[] $genres
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVSeason[] $tvSeason
 * @mixin \Eloquent
 * @property int $id
 * @property string $imdb_id
 * @property string $name
 * @property string $status
 * @property \Carbon\Carbon $first_aired
 * @property string|null $network
 * @property int|null $runtime
 * @property string|null $rating
 * @property string|null $director
 * @property string|null $writer
 * @property string|null $actors
 * @property string|null $summary
 * @property string|null $plot
 * @property string|null $country
 * @property string|null $poster
 * @property string|null $banner
 * @property float $imdb_score
 * @property float $imdb_votes
 * @property string|null $air_day
 * @property string|null $air_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereActors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereAirDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereAirTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereFirstAired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereImdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereImdbScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereImdbVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereNetwork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow wherePlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereRuntime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereWriter($value)
 * @property int|null $tvdb_id
 * @property int|null $tmdb_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereTvdbId($value)
 * @property string|null $homepage
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow whereHomepage($value)
 * @property float|null $popularity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country[] $countries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVShow wherePopularity($value)
 */
class TVShow extends Model
{
    use PresentableTrait;

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
    protected $fillable = ['imdb_id', 'name', 'status', 'first_aired', 'network', 'runtime', 'rating',
        'summary', 'plot', 'country', 'poster', 'banner', 'imdb_score', 'imdb_votes', 'air_day', 'air_time',
        'tvdb_id', 'tmdb_id', 'homepage', 'popularity', ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'first_aired'];

    /**
     * One-to-Many: one tv show can have many tv seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvSeason() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TVSeason::class, 'tv_show_id');
    }

    /**
     * Many-to-Many: one tv show can have many genres.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'genre_tv_show', 'tv_show_id', 'genre_id');
    }

    /**
     * Many-to-Many: one tv show can have many languages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_tv_show', 'tv_show_id', 'language_code');
    }

    /**
     * Many-to-Many: one tv show can have many countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_tv_show', 'tv_show_id', 'country_code');
    }

    /**
     * Helper function: one tv show has many episodes through their respective seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function episodes() : \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(TVEpisode::class, TVSeason::class, 'tv_show_id', 'tv_season_id');
    }

    /**
     * Many-to-Many: one tv show can have many networks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function networks()
    {
        return $this->belongsToMany(Network::class, 'network_tv_show', 'tv_show_id', 'network_id');
    }
}
