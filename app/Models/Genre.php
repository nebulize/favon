<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'genres';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Many-to-Many: one genre can have many tv shows
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows()
    {
        return $this->belongsToMany(TVShow::class, 'genre_tv_show', 'genre_id', 'tv_show_id');
    }

}
