<?php

namespace App\Http\Adapters;

class TVDBAdapter extends APIAdapter
{
    public const IDENTIFIER = 'tvdb';

    public function __construct($limit, $unit)
    {
        parent::__construct($limit, $unit);
    }

}