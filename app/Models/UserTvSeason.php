<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTvSeason
 * Pivot Model.
 *
 * @property int $id
 * @property int $user_id
 * @property int $tv_season_id
 * @property string $status
 * @property \Carbon\Carbon|null $completed_at
 * @property int $progress
 * @property int|null $score
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\TVSeason $tvSeason
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereTvSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvSeason whereUserId($value)
 * @mixin \Eloquent
 */
class UserTvSeason extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'user_tv_season';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tv_season_id',
        'status',
        'completed_at',
        'progress',
        'score',
    ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'completed_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvSeason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TVSeason::class, 'tv_season_id');
    }
}
