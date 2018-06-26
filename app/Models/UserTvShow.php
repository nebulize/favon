<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserTvShow
 * Pivot Model.
 *
 * @property int $user_id
 * @property int $tv_show_id
 * @property string $status
 * @property \Carbon\Carbon|null $completed_at
 * @property float $score
 * @property-read \App\Models\TVShow $tvShow
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvShow whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvShow whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvShow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvShow whereTvShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserTvShow whereUserId($value)
 * @mixin \Eloquent
 */
class UserTvShow extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'user_tv_show';

    /**
     * Don't set timestamps.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tv_show_id',
        'status',
        'completed_at',
        'score',
    ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = [
        'completed_at',
    ];

    /**
     * Fields that should be casted to a specific type.
     * @var array
     */
    protected $casts = [
        'score' => 'float',
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
    public function tvShow(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TVShow::class, 'tv_show_id');
    }
}
