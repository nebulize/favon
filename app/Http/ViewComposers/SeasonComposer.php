<?php

namespace App\Http\ViewComposers;

use App\Models\Season;
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
        $season = new Season();
        if (\in_array($month, [1, 2, 3], true)) {
            $season->start_date = Carbon::create($year, 1, 1);
            $season->name = 'Winter';
        } elseif (\in_array($month, [4, 5, 6], true)) {
            $season->start_date = Carbon::create($year, 4, 1);
            $season->name = 'Spring';
        } elseif (\in_array($month, [7, 8, 9], true)) {
            $season->start_date = Carbon::create($year, 7, 1);
            $season->name = 'Summer';
        } else {
            $season->start_date = Carbon::create($year, 10, 1);
            $season->name = 'Fall';
        }
        $season->year = $year;
        $this->season = $season;
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
