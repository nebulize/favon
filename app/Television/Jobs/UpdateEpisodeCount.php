<?php

namespace Favon\Television\Jobs;

use Illuminate\Bus\Queueable;
use Favon\Television\Models\TvSeason;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Favon\Television\Repositories\EpisodeRepository;

class UpdateEpisodeCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TvSeason
     */
    protected $tvSeason;

    /**
     * Create a new job instance.
     *
     * UpdateEpisodeCount constructor.
     * @param TvSeason $tvSeason
     */
    public function __construct(TvSeason $tvSeason)
    {
        $this->tvSeason = $tvSeason;
    }

    /**
     * Execute the job.
     *
     * @param EpisodeRepository $episodeRepository
     */
    public function handle(EpisodeRepository $episodeRepository): void
    {
        $this->tvSeason->episode_count = $episodeRepository->count(['tv_season_id' => $this->tvSeason->id]);
        $this->tvSeason->save();
    }
}
