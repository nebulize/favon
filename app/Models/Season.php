<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'seasons';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['name', 'year', 'start_date', 'end_date'];

    /**
     * Fields that are dates and casted to Carbon instances
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    /**
     * One-to-Many: one season has many tv seasons
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvSeasons()
    {
        return $this->hasMany(TVSeason::class,'season_id');
    }

}
