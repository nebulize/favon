<?php

namespace App\Jobs;

use App\Models\TVSeason;
use App\Repositories\TvEpisodeRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateEpisodeCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tvSeason;

    /**
     * Create a new job instance.
     *
     * UpdateEpisodeCount constructor.
     * @param TVSeason $tvSeason
     */
    public function __construct(TVSeason $tvSeason)
    {
        $this->tvSeason = $tvSeason;
    }

    /**
     * Execute the job.
     *
     * @param TvEpisodeRepository $tvEpisodeRepository
     */
    public function handle(TvEpisodeRepository $tvEpisodeRepository): void
    {
        $this->tvSeason->episode_count = $tvEpisodeRepository->count(['tv_season_id' => $this->tvSeason->id]);
        $this->tvSeason->save();
    }
}
