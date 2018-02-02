<?php

namespace App\Http\Clients;

use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use App\Http\Adapters\APIAdapter;
use bandwidthThrottle\tokenBucket\Rate;
use GuzzleHttp\Psr7\Request;

class OMDBClient
{
    protected const IDENTIFIER = 'omdb';

    /**
     * @var APIAdapter
     */
    protected $adapter;

    /**
     * Base URL
     * @var string
     */
    protected $url;

    /**
     * OMDBClient constructor.
     */
    public function __construct()
    {
        $this->adapter = new APIAdapter(2, Rate::SECOND, self::IDENTIFIER);
        $this->url = config('media.omdb_url') . '/?apikey=' . config('media.omdb_api_key');
    }

    /**
     * Get the response object for a TV show with the given IMDB ID
     *
     * @param string $imdbId
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function get(string $imdbId) : Response
    {
        $request = new Request('GET', $this->url . '&i=' . $imdbId);
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                throw new NoAPIResultsFoundException('OMDB: No results found for '.$imdbId);
                break;
            case 408:
                sleep(1);
                return $this->get($imdbId);
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }
}