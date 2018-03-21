<?php

namespace App\Http\Clients;

use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use App\Http\Adapters\APIAdapter;
use App\Http\Adapters\OMDBAdapter;
use App\Http\Responses\OMDB\OmdbResponse;

class OMDBClient
{
    /**
     * API Client Identifier.
     */
    protected const IDENTIFIER = 'omdb';

    /**
     * @var APIAdapter
     */
    protected $adapter;

    /**
     * Base URL.
     *
     * @var string
     */
    protected $url;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * OMDBClient constructor.
     * @param OMDBAdapter $adapter
     * @param LoggerInterface $logger
     */
    public function __construct(OMDBAdapter $adapter, LoggerInterface $logger)
    {
        $this->adapter = $adapter;
        $this->url = config('media.omdb_url').'/?apikey='.config('media.omdb_api_key');
        $this->logger = $logger;
    }

    /**
     * Get the response object for a TV show with the given IMDB ID.
     *
     * @param string $imdbId
     *
     * @return OmdbResponse
     */
    public function get(string $imdbId, int $tries = 0) : OmdbResponse
    {
        $request = new Request('GET', $this->url.'&i='.$imdbId);
        $response = $this->adapter->request($request);
        $result = new OmdbResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('OMDB: No results found for  '.$imdbId);
                break;
            default:
                $this->logger->error($response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }
}
