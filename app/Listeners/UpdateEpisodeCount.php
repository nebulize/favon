<?php

namespace App\Listeners;

use App\Events\TvSeasonUpdated;

class UpdateEpisodeCount
{
    /**
     * Handle the event.
     *
     * @param  TvSeasonUpdated  $event
     */
    public function handle(TvSeasonUpdated $event): void
    {
        \App\Jobs\UpdateEpisodeCount::dispatch($event->getTvSeason());
    }
}
