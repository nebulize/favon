<?php

namespace App\Services;


use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use App\Http\Clients\OMDBClient;
use Illuminate\Contracts\Logging\Log;

class OMDBService
{
    /**
     * @var OMDBClient
     */
    protected $client;

    /**
     * @var Log
     */
    protected $logger;

    /**
     * OMDBService constructor.
     * @param OMDBClient $client
     * @param Log $logger
     */
    public function __construct(OMDBClient $client, Log $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Get all application relevant data from an OMDB entry by IMDB id
     *
     * @param string $imdbId
     * @return array
     */
    public function get(string $imdbId) : array
    {
        try {
            $response = $this->client->get($imdbId);
        } catch (NoAPIResultsFoundException $e) {
            $this->logger->warning('Skipped ' . $imdbId . "\n" . $e->getMessage());
            return null;
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }

        return [
            'summary' => $response->getResponse()->Plot,
            'country' => $response->getResponse()->Country,
            'imdb_score' => (float)$response->getResponse()->imdbRating,
            'imdb_votes' => (int)str_replace(',', '', $response->getResponse()->imdbVotes),
            'genres' => explode(', ', $response->getResponse()->Genre),
        ];
    }

}