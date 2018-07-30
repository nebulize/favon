<?php

namespace Favon\Media\Models;

use Favon\Television\Models\TvShow;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Application\Models\Country
 *
 * @property int $code
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Country whereName($value)
 * @mixin \Eloquent
 */
class Country extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'countries';

    /**
     * Indicates whether model has timestamp fields or not.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Many-to-Many: one country can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'country_tv_show', 'country_code', 'tv_show_id');
    }
}
