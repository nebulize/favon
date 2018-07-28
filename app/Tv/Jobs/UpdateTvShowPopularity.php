<?php

namespace Favon\Tv\Jobs;

use Favon\Services\ApiService;
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
     * @param ApiService $apiService
     */
    public function handle(ApiService $apiService): void
    {
        $apiService->updatePopularity($this->id);
    }
}
