<?php

namespace Favon\Auth\Models;

use Favon\Television\Models\TvSeason;
use Favon\Television\Models\UserTvShow;
use Illuminate\Notifications\Notifiable;
use Favon\Television\Models\UserTvSeason;
use Favon\Auth\Jobs\SendResetPasswordEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Favon\Auth\Models\User.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 * @property bool $notify_messages
 * @property bool $notify_shows
 * @property bool $notify_features
 * @property bool $verified
 * @property string|null $email_token
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvSeason[] $tvSeasons
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\UserTvSeason[] $userTvSeasons
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\UserTvShow[] $userTvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereEmailToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereNotifyFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereNotifyMessages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereNotifyShows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Auth\Models\User whereVerified($value)
 * @mixin \Eloquent
 */
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
