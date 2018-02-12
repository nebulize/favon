<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TVShow[] $tvShows
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereName($value)
 */
class Language extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'languages';

    /**
     * Model has timestamp fields or not.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = ['name', 'code'];

    /**
     * Many-to-Many: one language can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows()
    {
        return $this->belongsToMany(TVShow::class, 'language_tv_show', 'language_code', 'tv_show_id');
    }
}
