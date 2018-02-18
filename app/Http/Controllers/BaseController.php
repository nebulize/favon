<?php

namespace App\Http\Controllers;

use App\Enumerators\SeasonType;

class BaseController extends Controller
{
    public function index($year, $season)
    {
        return view('tv.seasonal.index', [
            'seasonName' => ucfirst($season),
            'seasonYear' => $year
        ]);

    }
}
