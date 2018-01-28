<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TVShow extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'tv_shows';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['imdb_id', 'name', 'status', 'first_aired', 'network', 'runtime', 'rating', 'director',
        'writer', 'actors', 'summary', 'plot', 'country', 'poster', 'banner', 'imdb_score', 'imdb_votes',
        'air_day', 'air_time'];

    /**
     * Fields that are dates and casted to Carbon instances
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'first_aired'];

    /**
     * One-to-Many: one tv show can have many tv seasons
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvSeason()
    {
        return $this->hasMany(TVSeason::class, 'tv_show_id');
    }

    /**
     * Many-to-Many: one tv show can have many genres
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_tv_show', 'tv_show_id', 'genre_id');
    }

    /**
     * Many-to-Many: one tv show can have many languages
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_tv_show', 'tv_show_id', 'language_id');
    }

    /**
     * Helper function: one tv show has many episodes through their respective seasons
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function episodes()
    {
        return $this->hasManyThrough(TVEpisode::class, TVSeason::class, 'tv_show_id', 'tv_season_id');
    }

}
