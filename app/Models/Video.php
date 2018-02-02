<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Video
 *
 * @property-read \App\Models\TVSeason $tvSeason
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string $type
 * @property int $tv_season_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereTvSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Video whereUpdatedAt($value)
 */
class Video extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'videos';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['name', 'key', 'type', 'tv_season_id'];

    /**
     * One-To-Many: one video belongs to one tv season
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvSeason() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TVSeason::class, 'tv_season_id');
    }
}
