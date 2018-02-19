<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class TvSeasonPresenter extends Presenter
{
    public function summary()
    {
        if (strlen($this->entity->summary) > 130)
        {
            $lastPos = (130 - 3) - strlen($this->entity->summary);
            return substr($this->entity->summary, 0, strrpos($this->entity->summary, ' ', $lastPos)) . '...';
        }
        return $this->entity->summary;
    }

}