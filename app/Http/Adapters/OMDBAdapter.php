<?php

namespace App\Http\Adapters;

class OMDBAdapter extends APIAdapter
{
    public const IDENTIFIER = 'omdb';

    public function __construct($limit, $unit)
    {
        parent::__construct($limit, $unit);
    }

}