<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Season.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVSeason[] $tvSeasons
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereUpdatedAt($value)
 * @property int|null $year
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Season whereYear($value)
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
    protected $fillable = ['year', 'name', 'start_date', 'end_date'];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    /**
     * One-to-Many: one season has many tv seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvSeasons() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TVSeason::class, 'season_id');
    }
}
