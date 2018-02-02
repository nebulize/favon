<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TVSeason
 *
 * @property-read \App\Models\Season $season
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVEpisode[] $tvEpisodes
 * @property-read \App\Models\TVShow $tvShow
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
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
 */
class TVSeason extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'tv_seasons';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['first_aired', 'tv_show_id', 'season_id', 'number'];

    /**
     * Fields that are dates and casted to Carbon instances
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'first_aired'];

    /**
     * One-to-Many: one tv season has many tv episodes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvEpisodes()
    {
        return $this->hasMany(TVEpisode::class, 'tv_season_id');
    }

    /**
     * One-to-Many: one tv season belongs to one tv show
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvShow()
    {
        return $this->belongsTo(TVShow::class, 'tv_show_id');
    }

    /**
     * One-to-Many: one tv season belongs to one season
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    /**
     * Many-to-Many: one tv season can have many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tv_season', 'tv_season_id', 'user_id');
    }
}
