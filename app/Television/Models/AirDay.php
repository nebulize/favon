<?php

namespace Favon\Television\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\AirDay.
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\AirDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\AirDay whereName($value)
 * @mixin \Eloquent
 */
class AirDay extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_air_days';

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
     * One-To-Many: one tv status has many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TvShow::class, 'tv_air_day_id');
    }
}
