<?php

namespace Favon\Tv\Models;

use Favon\Media\Models\ListStatus;
use Favon\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

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
