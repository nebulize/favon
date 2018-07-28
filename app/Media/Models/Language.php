<?php

namespace Favon\Media\Models;

use Favon\Tv\Models\TvShow;
use Illuminate\Database\Eloquent\Model;

/**
 * Favon\Application\Models\Language
 *
 * @property string $name
 * @property int $code
 * @property-read \Illuminate\Database\Eloquent\Collection|\Favon\Tv\Models\TvShow[] $tvShows
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Language whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Favon\Media\Models\Language whereName($value)
 * @mixin \Eloquent
 */
class Language extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'languages';

    /**
     * Indicates whether model has timestamp fields or not.
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
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Many-to-Many: one language can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'language_tv_show', 'language_code', 'tv_show_id');
    }
}
