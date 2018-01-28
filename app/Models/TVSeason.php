<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

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
    protected $fillable = ['first_aired', 'tv_show_id', 'season_id'];

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
