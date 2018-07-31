<?php

namespace Favon\Media\Jobs;

use Psr\Log\LoggerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Favon\Media\Http\Clients\TmdbMediaClient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Favon\Media\Repositories\PersonRepository;
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
     * @param TmdbMediaClient $tmdbClient
     * @param PersonRepository $personRepository
     * @param LoggerInterface $logger
     */
    public function handle(TmdbMediaClient $tmdbClient, PersonRepository $personRepository, LoggerInterface $logger): void
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
//            $apiService->fetchImages('profile', $newPerson['photo']);
//        }
    }
}
