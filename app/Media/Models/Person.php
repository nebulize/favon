<?php

namespace Favon\Media\Models;

use Favon\Tv\Models\TvSeason;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'persons';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'birthday',
        'deathday',
        'name',
        'gender',
        'biography',
        'place_of_birth',
        'photo',
        'tmdb_id',
    ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'birthday',
        'deathday',
    ];

    /**
     * Many-To-Many: one person can star in many tv seasons.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvSeasons() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvSeason::class, 'person_tv_season', 'person_id', 'tv_season_id');
    }
}
