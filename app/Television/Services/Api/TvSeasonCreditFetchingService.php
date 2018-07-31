<?php

namespace Favon\Television\Services\Api;

use Favon\Television\Models\TvSeason;
use Favon\Media\Http\Clients\TmdbMediaClient;
use Favon\Media\Repositories\PersonRepository;
use Favon\Television\Http\Clients\TmdbTvClient;
use Favon\Television\Repositories\TvSeasonRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Favon\Application\Exceptions\NoAPIResultsFoundException;

class TvSeasonCreditFetchingService
{
    /**
     * @var TmdbTvClient
     */
    protected $tmdbTvClient;

    /**
     * @var TmdbMediaClient
     */
    protected $tmdbClient;

    /**
     * @var PersonRepository
     */
    protected $personRepository;

    /**
     * @var TvSeasonRepository
     */
    protected $tvSeasonRepository;

    /**
     * TvSeasonCreditFetchingService constructor.
     * @param TmdbTvClient $tmdbTvClient
     * @param TmdbMediaClient $tmdbClient
     * @param PersonRepository $personRepository
     * @param TvSeasonRepository $tvSeasonRepository
     */
    public function __construct(TmdbTvClient $tmdbTvClient, TmdbMediaClient $tmdbClient, PersonRepository $personRepository,
                                TvSeasonRepository $tvSeasonRepository)
    {
        $this->tmdbTvClient = $tmdbTvClient;
        $this->tmdbClient = $tmdbClient;
        $this->personRepository = $personRepository;
        $this->tvSeasonRepository = $tvSeasonRepository;
    }

    /**
     * Fetch and store all credits for a tv season.
     *
     * @param int $id
     * @param TvSeason $tvSeason
     *
     * @throws NoAPIResultsFoundException
     */
    public function execute(int $id, TvSeason $tvSeason): void
    {
        $tvSeasonCreditsResponse = $this->tmdbTvClient->getTvSeasonCredits($id, $tvSeason->number);

        if ($tvSeasonCreditsResponse->hasBeenSuccessful() === false) {
            throw new NoAPIResultsFoundException(__CLASS__.': No results found for id '.$id.' S#'.$tvSeason->number);
        }

        foreach ($tvSeasonCreditsResponse->getResults() as $result) {
            if ($result->getTmdbPersonId() === null) {
                continue;
            }

            try {
                $person = $this->personRepository->find([
                    'tmdb_id' => $result->getTmdbPersonId(),
                ]);
            } catch (ModelNotFoundException $exception) {
                $personResponse = $this->tmdbClient->getPerson($result->getTmdbPersonId());
                if ($personResponse->hasBeenSuccessful() === false) {
                    continue;
                }
                $person = $this->personRepository->create($personResponse->toArray());
            }
            $this->tvSeasonRepository->addPerson($tvSeason, $person, $result->toArray());
        }
    }
}
