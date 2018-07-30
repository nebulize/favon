<?php

namespace Favon\Television\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\Network
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Network whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Network whereName($value)
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
    protected $fillable = [
        'name',
    ];

    /**
     * Many-to-Many: one network can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'network_tv_show', 'network_id', 'tv_show_id');
    }
}
