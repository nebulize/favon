<?php

namespace App\Http\ViewComposers;

use App\Enumerators\SeasonType;
use Carbon\Carbon;
use Illuminate\View\View;

class SeasonComposer
{
    /**
     * @var string
     */
    protected $season;

    /**
     * SeasonComposer constructor.
     */
    public function __construct()
    {
        $date = Carbon::now();
        $month = $date->month;
        $year = $date->year;
        if (\in_array($month, [1, 2, 3], true)) {
            $this->season = Carbon::create($year, 1, 1);
        } elseif (\in_array($month, [4, 5, 6], true)) {
            $this->season = Carbon::create($year, 4, 1);
        } elseif (\in_array($month, [7, 8, 9], true)) {
            $this->season = Carbon::create($year, 7, 1);
        } else {
            $this->season = Carbon::create($year, 10, 1);
        }
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     */
    public function compose(View $view): void
    {
        $view->with('currentSeason', $this->season);
    }
}