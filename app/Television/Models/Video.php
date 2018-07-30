<?php

namespace Favon\Television\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\Video
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string|null $type
 * @property int $tv_season_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Favon\Television\Models\TvSeason $tvSeason
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereTvSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\Video whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_videos';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'type',
        'tv_season_id',
    ];

    /**
     * One-To-Many: one video belongs to one tv season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvSeason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvSeason::class, 'tv_season_id');
    }
}
