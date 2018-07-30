<?php

namespace Favon\Media\Models;

use Favon\Television\Models\TvSeason;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Media\Models\Person.
 *
 * @property int $id
 * @property \Carbon\Carbon|null $birthday
 * @property \Carbon\Carbon|null $deathday
 * @property string $name
 * @property string|null $gender
 * @property string|null $biography
 * @property string|null $place_of_birth
 * @property string|null $photo
 * @property float $tmdb_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Television\Models\TvSeason[] $tvSeasons
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereBiography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereDeathday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereTmdbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Person whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Person extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'persons';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'birthday',
        'deathday',
        'name',
        'gender',
        'biography',
        'place_of_birth',
        'photo',
        'tmdb_id',
    ];

    /**
     * Fields that are dates and casted to Carbon instances.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'birthday',
        'deathday',
    ];

    /**
     * Many-To-Many: one person can star in many tv seasons.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvSeasons() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvSeason::class, 'person_tv_season', 'person_id', 'tv_season_id');
    }
}
