<?php

namespace App\Models;

use App\Presenters\TvSeasonPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * App\Models\TVSeason.
 *
 * @property-read \App\Models\Season $season
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVEpisode[] $tvEpisodes
 * @property-read \App\Models\TVShow $tvShow
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon $first_aired
 * @property int $tv_show_id
 * @property int $season_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereFirstAired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereTvShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereUpdatedAt($value)
 * @property int $number
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person[] $persons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Video[] $videos
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereNumber($value)
 * @property string|null $summary
 * @property string|null $poster
 * @property int $tmdb_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereTmdbId($value)
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereName($value)
 * @property int $episode_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVSeason whereEpisodeCount($value)
 */
class TVSeason extends Model
{
    use PresentableTrait;

    protected $presenter = TvSeasonPresenter::class;

    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_seasons';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = ['first_aired', 'summary', 'poster', 'tmdb_id', 'tv_show_id', 'season_id', 'number', 'name', 'episode_count'];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'first_aired'];

    /**
     * One-to-Many: one tv season has many tv episodes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvEpisodes() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TVEpisode::class, 'tv_season_id');
    }

    /**
     * One-to-Many: one tv season belongs to one tv show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvShow() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TVShow::class, 'tv_show_id');
    }

    /**
     * One-to-Many: one tv season belongs to one season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    /**
     * Many-to-Many: one tv season can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_tv_season', 'tv_season_id', 'user_id')
            ->withTimestamps()
            ->withPivot('status', 'completed_at', 'progress', 'score');
    }

    /**
     * One-To-Many: one tv season has many videos.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Video::class, 'tv_season_id');
    }

    /**
     * Many-To-Many: one tv season has many persons in it.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function persons() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_tv_season', 'tv_season_id', 'person_id');
    }
}
