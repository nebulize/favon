<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class TvSeasonPresenter extends Presenter
{
    public function summary()
    {
        if (strlen($this->summary) > 130)
        {
            $lastPos = (130 - 3) - strlen($this->summary);
            return substr($this->summary, 0, strrpos($this->summary, ' ', $lastPos)) . '...';
        }
        return $this->summary;
    }

}