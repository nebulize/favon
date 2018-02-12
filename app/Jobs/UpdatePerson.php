<?php

namespace App\Jobs;

use App\Services\TMDBService;
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
     * @param TMDBService $tmdbService
     * @param PersonRepository $personRepository
     */
    public function handle(TMDBService $tmdbService, PersonRepository $personRepository, Log $logger)
    {
        $newPerson = $tmdbService->getPerson($this->id);
        if ($newPerson === null) {
            return;
        }
        try {
            $oldPerson = $personRepository->find([
                'tmdb_id' => $this->id,
            ]);
//            if ($oldPerson->photo !== null) {
//                unlink(public_path('images/profile/w185/'.$oldPerson->photo));
//            }
            $personRepository->update($oldPerson, $newPerson);
            $logger->info('Updated person: '.$this->id);
        } catch (ModelNotFoundException $e) {
            $personRepository->create($newPerson);
            $logger->info('Created new person: '.$this->id);
        }
//        if (!empty($newPerson['photo'])) {
//            $tmdbService->fetchImages('profile', $newPerson['photo']);
//        }
    }
}
