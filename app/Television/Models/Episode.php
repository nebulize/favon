<?php

namespace Favon\Television\Models;

use Favon\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\Episode
 *
 * @property int $id
 * @property int $number
 * @property string|null $name
 * @property \Carbon\Carbon|null $first_aired
 * @property string|null $plot
 * @property int $tmdb_id
 * @property int $tv_season_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Favon\Television\Models\TvSeason $tvSeason
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Auth\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereFirstAired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode wherePlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereTvSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Episode whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Episode extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_episodes';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'number',
        'name',
        'first_aired',
        'plot',
        'tmdb_id',
        'tv_season_id',
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
     * One-to-Many: one tv episode belongs to one tv season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvSeason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvSeason::class, 'tv_season_id');
    }

    /**
     * Many-to-Many: one tv show can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tv_episode', 'tv_episode_id', 'user_id');
    }
}
