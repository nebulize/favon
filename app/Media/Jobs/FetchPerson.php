<?php

namespace Favon\Media\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Favon\Media\Http\Clients\TmdbMediaClient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Favon\Media\Repositories\PersonRepository;

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
     * @param TmdbMediaClient $tmdbClient
     * @param PersonRepository $personRepository
     */
    public function handle(TmdbMediaClient $tmdbClient, PersonRepository $personRepository): void
    {
        $personResponse = $tmdbClient->getPerson($this->id);
        if ($personResponse->hasBeenSuccessful() === false) {
            return;
        }
        $personRepository->create($personResponse->toArray());
//        if (!empty($person['photo'])) {
//            $apiService->fetchImages('profile', $person['photo']);
//        }
    }
}
