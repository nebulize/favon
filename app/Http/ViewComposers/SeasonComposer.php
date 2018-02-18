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
        if (\in_array($month, [1, 2, 3], true)) {
            $this->season = SeasonType::WINTER;
        } elseif (\in_array($month, [4, 5, 6], true)) {
            $this->season = SeasonType::SPRING;
        } elseif (\in_array($month, [7, 8, 9], true)) {
            $this->season = SeasonType::SUMMER;
        } else {
            $this->season = SeasonType::FALL;
        }
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     */
    public function compose(View $view): void
    {
        $view->with('season', $this->season);
    }
}