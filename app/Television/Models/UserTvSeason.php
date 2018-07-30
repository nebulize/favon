<?php

namespace Favon\Television\Models;

use Favon\Auth\Models\User;
use Favon\Media\Models\ListStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Television\Models\UserTvSeason.
 *
 * @property int $id
 * @property int $user_id
 * @property int $tv_season_id
 * @property \Carbon\Carbon|null $completed_at
 * @property int $progress
 * @property int|null $score
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $list_status_id
 * @property-read \Favon\Media\Models\ListStatus|null $status
 * @property-read \Favon\Television\Models\TvSeason $tvSeason
 * @property-read \Favon\Auth\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereListStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereTvSeasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Television\Models\UserTvSeason whereUserId($value)
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
        'list_status_id',
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
     * One-to-Many (Many-to-Many with pivot table model): one user tv season entry belongs to one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * One-to-Many (Many-to-Many with pivot table model): one user tv season entry belongs to one tv season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tvSeason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TvSeason::class, 'tv_season_id');
    }

    /**
     * One-to-Many: one user tv season entry belongs to one list status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ListStatus::class, 'list_status_id');
    }
}
