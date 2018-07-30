<?php

namespace Favon\Television\Listeners;

use Favon\Television\Events\TvSeasonUpdated;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Favon\Television\Jobs\UpdateEpisodeCount as UpdateEpisodeCountJob;

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
