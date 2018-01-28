<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * Table name
     * @var string
     */
    protected $table = 'languages';

    /**
     * Fields that should be mass assignable
     * @var array
     */
    protected $fillable = ['name', 'code'];

    /**
     * Many-to-Many: one language can have many tv shows
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows()
    {
        return $this->belongsToMany(TVShow::class, 'language_tv_show', 'language_id', 'tv_show_id');
    }

}
