<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TVEpisode
 *
 * @property-read \App\Models\TVSeason $tvSeason
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @mixin \Eloquent
 * @property int $id
 * @property int $number
 * @property string|null $name
 * @property \Carbon\Carbon|null $first_aired
 * @property string|null $plot
 * @property int $tv_season_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereFirstAired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode wherePlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereTvSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TVEpisode whereUpdatedAt($value)
 */
class TVEpisode extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'tv_episodes';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['number', 'name', 'first_aired', 'plot', 'tvdb_id', 'tv_season_id'];

    /**
     * Fields that are dates and casted to Carbon instances
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'first_aired'];

    /**
     * One-to-Many: one tv episode belongs to one tv season
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvSeason()
    {
        return $this->belongsTo(TVSeason::class, 'tv_season_id');
    }

    /**
     * Many-to-Many: one tv show can have many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tv_episode', 'tv_episode_id', 'user_id');
    }

}
