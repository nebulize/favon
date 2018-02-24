<?php

namespace App\Jobs;

use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateShow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $id;

    /**
     * UpdateShow constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Fetch new show and update it in our database.
     *
     * @param ApiService $apiService
     */
    public function handle(ApiService $apiService): void
    {
        $apiService->updateTvShow($this->id);
    }
}
