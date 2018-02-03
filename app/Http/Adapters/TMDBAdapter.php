<?php

namespace App\Http\Adapters;

class TMDBAdapter extends APIAdapter
{
    public const IDENTIFIER = 'tmdb';

    public function __construct($limit, $unit)
    {
        parent::__construct($limit, $unit);
    }

}