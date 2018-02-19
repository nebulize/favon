<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class TvShowPresenter extends Presenter
{
    public function summary()
    {
        if (strlen($this->entity->summary) > 150)
        {
            $lastPos = (150 - 3) - strlen($this->entity->summary);
            return substr($this->entity->summary, 0, strrpos($this->entity->summary, ' ', $lastPos)) . '...';
        }
        return $this->entity->summary;
    }

}