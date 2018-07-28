<?php

namespace Favon\Media\Models;

use Favon\Tv\Models\TvSeason;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Application\Models\Season
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $year
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Tv\Models\TvSeason[] $tvSeasons
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Season whereYear($value)
 * @mixin \Eloquent
 */
class Season extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'seasons';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'year',
        'name',
        'start_date',
        'end_date',
    ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];

    /**
     * One-to-Many: one season has many tv seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvSeasons() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TvSeason::class, 'season_id');
    }
}
