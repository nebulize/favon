<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Genre.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVShow[] $tvShows
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Genre whereName($value)
 */
class Genre extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'genres';

    /**
     * Model has timestamp fields or not.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Many-to-Many: one genre can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows() : BelongsToMany
    {
        return $this->belongsToMany(TVShow::class, 'genre_tv_show', 'genre_id', 'tv_show_id');
    }
}
