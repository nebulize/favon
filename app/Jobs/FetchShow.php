<?php

namespace App\Jobs;

use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchShow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int TMDB id
     */
    protected $id;

    /**
     * FetchShow constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle(ApiService $apiService)
    {
        $tvShow = $apiService->fetchTvShow($this->id);
        if ($tvShow === null) {
            return;
        }
    }
}
