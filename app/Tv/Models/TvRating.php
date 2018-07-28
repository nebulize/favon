<?php

namespace Favon\Tv\Models;

use Illuminate\Database\Eloquent\Model;

class TvRating extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_ratings';

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
    ];

    /**
     * One-To-Many: one tv rating has many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TvShow::class, 'tv_rating_id');
    }
}
