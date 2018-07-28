<?php

namespace Favon\Tv\Models;

use Favon\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

class TvEpisode extends Model
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
