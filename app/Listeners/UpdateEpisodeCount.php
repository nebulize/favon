<?php

namespace App\Listeners;

use App\Events\TvSeasonUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
