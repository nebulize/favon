<?php

namespace Favon\Television\Listeners;

use Favon\Television\Events\TvSeasonUpdated;
use Favon\Television\Jobs\UpdateEpisodeCount as UpdateEpisodeCountJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UpdateEpisodeCount
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  TvSeasonUpdated  $event
     */
    public function handle(TvSeasonUpdated $event): void
    {
        $this->dispatch(new UpdateEpisodeCountJob($event->getTvSeason()));
    }
}
