<?php

namespace Favon\Media\Jobs;

use Psr\Log\LoggerInterface;
use Illuminate\Bus\Queueable;
use Favon\Http\Clients\TmdbClient;
use Favon\Media\Repositories\PersonRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdatePerson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $id;

    /**
     * UpdatePerson constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param TmdbClient $tmdbClient
     * @param PersonRepository $personRepository
     * @param LoggerInterface $logger
     */
    public function handle(TmdbClient $tmdbClient, PersonRepository $personRepository, LoggerInterface $logger): void
    {
        $personResponse = $tmdbClient->getPerson($this->id);
        if ($personResponse->hasBeenSuccessful() === false) {
            return;
        }
        try {
            $person = $personRepository->find([
                'tmdb_id' => $this->id,
            ]);
//            if ($oldPerson->photo !== null) {
//                unlink(public_path('images/profile/w185/'.$oldPerson->photo));
//            }
            $personRepository->update($person, $personResponse->toArray());
            $logger->info('Updated person: '.$this->id);
        } catch (ModelNotFoundException $exception) {
            $personRepository->create($personResponse->toArray());
            $logger->info('Created new person: '.$this->id);
        }
//        if (!empty($newPerson['photo'])) {
//            $tmdbService->fetchImages('profile', $newPerson['photo']);
//        }
    }
}
