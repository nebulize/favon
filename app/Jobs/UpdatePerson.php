<?php

namespace App\Jobs;

use App\Repositories\PersonRepository;
use App\Services\TMDBService;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdatePerson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * @param TMDBService $tmdbService
     * @param PersonRepository $personRepository
     */
    public function handle(TMDBService $tmdbService, PersonRepository $personRepository)
    {
        $newPerson = $tmdbService->getPerson($this->id);
        try {
            $oldPerson = $personRepository->find([
                'tmdb_id' => $this->id
            ]);
            if ($oldPerson->photo !== null) {
                unlink(public_path('images/profile/w185/'.$oldPerson->photo));
            }
            $personRepository->update($oldPerson, $newPerson);
        } catch (ModelNotFoundException $e) {
            $personRepository->create($newPerson);
        }
        if (!empty($newPerson['photo'])) {
            $tmdbService->fetchImages('profile', $newPerson['photo']);
        }
    }
}
