<?php

namespace Favon\Tv\Models;

use Illuminate\Database\Eloquent\Model;

class TvVideo extends Model
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
