<?php

namespace App\Models;

use App\Jobs\SendResetPasswordEmail;
use App\Mail\ResetPassword;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;

/**
 * App\User.
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $avatar
 * @property float $filter_score
 * @property int $filter_votes
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFilterScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFilterVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @property bool $notify_messages
 * @property bool $notify_shows
 * @property bool $notify_features
 * @property bool $verified
 * @property string|null $token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNotifyFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNotifyMessages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNotifyShows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVerified($value)
 * @property string|null $email_token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailToken($value)
 */
class User extends Authenticatable
{
    use Notifiable, DispatchesJobs;

    const WATCH_STATUS = [
        'ptw' => 'Plan to Watch',
        'watching' => 'Watching',
        'dropped' => 'Dropped',
        'completed' => 'Completed',
        'hold' => 'On Hold'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'notify_messages', 'notify_shows', 'notify_features'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->dispatch(new SendResetPasswordEmail($this, $token));
    }
}
