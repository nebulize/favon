<?php

namespace App\Jobs;

use App\Repositories\PersonRepository;
use App\Services\TMDBService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchPerson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $id;

    /**
     * FetchPerson constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param TMDBService $tmdbService
     * @param PersonRepository $personRepository
     */
    public function handle(TMDBService $tmdbService, PersonRepository $personRepository)
    {
        $person = $tmdbService->getPerson($this->id);
        if ($person === null) {
            return;
        }
        $personRepository->create($person);
        if (!empty($person['photo'])) {
            $tmdbService->fetchImages('profile', $person['photo']);
        }
    }
}
