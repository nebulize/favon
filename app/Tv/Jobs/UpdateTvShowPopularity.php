<?php

namespace Favon\Tv\Jobs;

use Favon\Tv\Services\Api\PopularityUpdatingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateTvShowPopularity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $id;

    /**
     * UpdateTvShowPopularity constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param PopularityUpdatingService $service
     */
    public function handle(PopularityUpdatingService $service): void
    {
        $service->execute($this->id);
    }
}
