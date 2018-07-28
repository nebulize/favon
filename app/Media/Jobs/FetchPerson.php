<?php

namespace Favon\Media\Jobs;

use Illuminate\Bus\Queueable;
use Favon\Http\Clients\TmdbClient;
use Favon\Media\Repositories\PersonRepository;
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
     * @param TmdbClient $tmdbClient
     * @param PersonRepository $personRepository
     */
    public function handle(TmdbClient $tmdbClient, PersonRepository $personRepository): void
    {
        $personResponse = $tmdbClient->getPerson($this->id);
        if ($personResponse->hasBeenSuccessful() === false) {
            return;
        }
        $personRepository->create($personResponse->toArray());
//        if (!empty($person['photo'])) {
//            $tmdbService->fetchImages('profile', $person['photo']);
//        }
    }
}
