<?php

namespace App\Events;

use App\Models\TVSeason;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TvSeasonUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var TVSeason
     */
    protected $tvSeason;

    /**
     * TvSeasonUpdated constructor.
     * @param TVSeason $tvSeason
     */
    public function __construct(TVSeason $tvSeason)
    {
        $this->tvSeason = $tvSeason;
    }

    /**
     * @return TVSeason
     */
    public function getTvSeason(): TVSeason
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
