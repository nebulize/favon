<?php

namespace App\Jobs;

use App\Http\Clients\TMDBClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Logging\Log;
use App\Repositories\PersonRepository;
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
     * @var
     */
    protected $logger;

    /**
     * UpdatePerson constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param TMDBClient $tmdbClient
     * @param PersonRepository $personRepository
     * @param Log $logger
     */
    public function handle(TMDBClient $tmdbClient, PersonRepository $personRepository, Log $logger)
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
        } catch (ModelNotFoundException $e) {
            $personRepository->create($personResponse->toArray());
            $logger->info('Created new person: '.$this->id);
        }
//        if (!empty($newPerson['photo'])) {
//            $tmdbService->fetchImages('profile', $newPerson['photo']);
//        }
    }
}
