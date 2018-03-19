<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Network
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Network whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Network whereName($value)
 * @mixin \Eloquent
 */
class Network extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'networks';

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
     * Many-to-Many: one network can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows()
    {
        return $this->belongsToMany(TVShow::class, 'network_tv_show', 'network_id', 'tv_show_id');
    }
}
