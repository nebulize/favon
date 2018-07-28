<?php

namespace Favon\Tv\Listeners;

use Favon\Tv\Events\TvSeasonUpdated;
use Favon\Tv\Jobs\UpdateEpisodeCount as UpdateEpisodeCountJob;
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
