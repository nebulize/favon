<?php

namespace Favon\Media\Models;

use Favon\Tv\Models\UserTvSeason;
use Favon\Tv\Models\UserTvShow;
use Illuminate\Database\Eloquent\Model;

class ListStatus extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'list_statuses';

    /**
     * Indicates whether model has timestamp fields or not.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * One-To-Many: one list status has many user tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvShow::class, 'list_status_id');
    }

    /**
     * One-To-Many: one list status has many user tv seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvSeasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvSeason::class, 'list_status_id');
    }
}
