<?php

namespace Favon\Television\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionStatus extends Model
{
    /**
     * Table name.
     * @var string
     */
    protected $table = 'tv_statuses';

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
    ];

    /**
     * One-To-Many: one tv status has many tv shows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tvShows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TvShow::class, 'tv_status_id');
    }
}
