<?php

namespace Favon\Television\Models;

use Favon\Media\Models\ListStatus;
use Favon\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\UserTvShow
 *
 * @property int $id
 * @property int $user_id
 * @property int $tv_show_id
 * @property \Carbon\Carbon|null $completed_at
 * @property float $score
 * @property int|null $list_status_id
 * @property-read \Favon\Media\Models\ListStatus|null $status
 * @property-read \Favon\Television\Models\TvShow $tvShow
 * @property-read \Favon\Auth\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvShow whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvShow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvShow whereListStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvShow whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvShow whereTvShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvShow whereUserId($value)
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
        'list_status_id',
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
     * One-to-Many (Many-to-Many with pivot table model): one user tv show entry belongs to one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * One-to-Many (Many-to-Many with pivot table model): one user tv show entry belongs to one tv show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvShow(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }

    /**
     * One-to-Many: one user tv show entry belongs to one list status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ListStatus::class, 'list_status_id');
    }
}
