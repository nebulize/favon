<?php

namespace App\Http\Clients;

use App\Http\Responses\OMDB\OmdbResponse;
use GuzzleHttp\Psr7\Request;
use App\Http\Adapters\APIAdapter;
use App\Http\Adapters\OMDBAdapter;
use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use Illuminate\Contracts\Logging\Log;

class OMDBClient
{
    /**
     * API Client Identifier
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
     * @var Log
     */
    protected $logger;

    /**
     * OMDBClient constructor.
     * @param OMDBAdapter $adapter
     * @param Log $logger
     */
    public function __construct(OMDBAdapter $adapter, Log $logger)
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
    public function get(string $imdbId) : OmdbResponse
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
            case 408:
                sleep(1);
                return $this->get($imdbId);
            default:
                $this->logger->error($response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }
}
