<?php

namespace Favon\Media\Models;

use Favon\Television\Models\UserTvShow;
use Illuminate\Database\Eloquent\Model;
use Favon\Television\Models\UserTvSeason;

/**
 * Favon\Media\Models\ListStatus.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\UserTvSeason[] $userTvSeasons
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\UserTvShow[] $userTvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\ListStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\ListStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\ListStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\ListStatus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\ListStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ListStatus extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'list_statuses';

    /**
     * Indicates whether model has timestamp fields or not.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * One-To-Many: one list status has many user tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvShow::class, 'list_status_id');
    }

    /**
     * One-To-Many: one list status has many user tv seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTvSeasons(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTvSeason::class, 'list_status_id');
    }
}
