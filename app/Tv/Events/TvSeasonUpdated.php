<?php

namespace Favon\Tv\Events;

use Favon\Tv\Models\TvSeason;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TvSeasonUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var TvSeason
     */
    protected $tvSeason;

    /**
     * TvSeasonUpdated constructor.
     * @param TvSeason $tvSeason
     */
    public function __construct(TvSeason $tvSeason)
    {
        $this->tvSeason = $tvSeason;
    }

    /**
     * @return TvSeason
     */
    public function getTvSeason(): TvSeason
    {
        return $this->tvSeason;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
