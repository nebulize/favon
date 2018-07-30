<?php

namespace Favon\Television\Models;

use Favon\Auth\Models\User;
use Favon\Media\Models\Person;
use Favon\Media\Models\Season;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Favon\Television\Presenters\TvSeasonPresenter;

/**
 * Favon\Television\Models\TvSeason.
 *
 * @property int $id
 * @property int $number
 * @property string|null $name
 * @property \Carbon\Carbon|null $first_aired
 * @property string|null $summary
 * @property string|null $poster
 * @property int $tmdb_id
 * @property int $tv_show_id
 * @property int|null $season_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $episode_count
 * @property float $rating
 * @property int $members_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\Episode[] $episodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Media\Models\Person[] $persons
 * @property-read \Favon\Media\Models\Season|null $season
 * @property-read \Favon\Television\Models\TvShow $tvShow
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\UserTvSeason[] $userTvSeasons
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Auth\Models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\Video[] $videos
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereEpisodeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereFirstAired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereMembersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason wherePoster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereTvShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\TvSeason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TvSeason extends Model
{
    use PresentableTrait;

    /**
     * Presenter class for formatting and presenting information.
     * @var string
     */
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
    protected $fillable = [
        'number',
        'name',
        'first_aired',
        'summary',
        'poster',
        'tmdb_id',
        'tv_show_id',
        'season_id',
        'episode_count',
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
        'rating' => 'float',
    ];

    /**
     * One-to-Many: one tv season has many tv episodes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function episodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Episode::class, 'tv_season_id');
    }

    /**
     * One-to-Many: one tv season belongs to one tv show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvShow(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }

    /**
     * One-to-Many: one tv season belongs to one season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    /**
     * Many-to-Many: one tv season can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_tv_season', 'tv_season_id', 'user_id')
            ->withTimestamps()
            ->withPivot('list_status_id', 'completed_at', 'progress', 'score');
    }

    /**
     * One-To-Many: one tv season has many videos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function videos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Video::class, 'tv_season_id');
    }

    /**
     * Many-To-Many: one tv season has many persons in it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function persons(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_tv_season', 'tv_season_id', 'person_id');
    }

    /**
     * One-to-Many (Many-to-Many with pivot table model): one tv seasons has many user tv season entries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvSeasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvSeason::class, 'tv_season_id');
    }
}
