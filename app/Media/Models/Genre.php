<?php

namespace Favon\Media\Models;

use Favon\Television\Models\TvShow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Favon\Application\Models\Genre.
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Genre whereName($value)
 * @mixin \Eloquent
 */
class Genre extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'genres';

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
     * Many-to-Many: one genre can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows() : BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'genre_tv_show', 'genre_id', 'tv_show_id');
    }
}
