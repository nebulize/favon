<?php

namespace Favon\Auth\Models;

use Favon\Auth\Jobs\SendResetPasswordEmail;
use Favon\Television\Models\TvSeason;
use Favon\Television\Models\UserTvSeason;
use Favon\Television\Models\UserTvShow;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, DispatchesJobs;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'notify_messages',
        'notify_shows',
        'notify_features',
        'verified',
        'email_token',
    ];

    /**
     * The attributes that should be visible for arrays.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'avatar',
        'pivot',
    ];

    /**
     * Boot the model.
     */
    public static function boot(): void
    {
        parent::boot();
        static::creating(function (User $user) {
            $user->email_token = str_random(30);
        });
    }

    /**
     * Many-To-Many: List of tv seasons for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvSeasons(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvSeason::class, 'user_tv_season', 'user_id', 'tv_season_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvSeasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvSeason::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvShow::class, 'user_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->dispatch(new SendResetPasswordEmail($this, $token));
    }
}
