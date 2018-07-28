<?php

namespace Favon\Tv\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'networks';

    /**
     * Model has timestamp fields or not.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fields that should be mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Many-to-Many: one network can have many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(TvShow::class, 'network_tv_show', 'network_id', 'tv_show_id');
    }
}
