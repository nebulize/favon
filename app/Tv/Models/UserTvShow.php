<?php

namespace Favon\Tv\Models;

use Favon\Media\Models\ListStatus;
use Favon\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;

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
<<<<<<< HEAD:app/Models/UserTvShow.php
=======
     * One-to-Many (Many-to-Many with pivot table model): one user tv show entry belongs to one user.
     *
>>>>>>> 175f359... Refactor backend into DDD structure:app/Tv/Models/UserTvShow.php
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
