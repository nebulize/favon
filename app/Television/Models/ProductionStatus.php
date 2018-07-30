<?php

namespace Favon\Television\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\ProductionStatus
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\ProductionStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\ProductionStatus whereName($value)
 * @mixin \Eloquent
 */
class ProductionStatus extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'production_statuses';

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
     * One-To-Many: one production status has many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TvShow::class, 'production_status_id');
    }
}
