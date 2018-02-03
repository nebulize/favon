<?php

namespace App\Http\Clients;

use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use App\Http\Adapters\APIAdapter;
use App\Http\Adapters\TMDBAdapter;
use GuzzleHttp\Psr7\Request;

class TMDBClient
{
    /**
     * @var APIAdapter
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $key;

    /**
     * TMDBClient constructor.
     * @param TMDBAdapter $adapter
     */
    public function __construct(TMDBAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->url = config('media.tmdb_url');
        $this->key = config('media.tmdb_api_key');
    }

    /**
     * Get the response object for a person with the given id
     *
     * @param int $id
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function getPerson(int $id) : Response
    {
        $request = new Request('GET', $this->url . '/person/' . $id . '?api_key=' . $this->key . '&language=en-US');
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                throw new NoAPIResultsFoundException('TMDB - Person: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

    /**
     * Get the response object for a list of changed person entries
     *
     * @param string|null $start_date
     * @param string|null $end_date
     * @return Response
     * @throws GenericAPIException
     */
    public function getChangedPersons(string $start_date = null, string $end_date = null) : Response
    {
        $url = $this->url . '/person/changes?api_key=' . $this->key . '&language=en-US';
        if ($start_date) {
            $url .= '&start_date='.$start_date;
        }
        if ($end_date) {
            $url .= '&end_date='.$end_date;
        }
        $request = new Request('GET', $url);
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        if (!$result->getHttpStatusCode() === 200) {
            throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        $result->setSuccessful();
        $result->setResponse(json_decode($response->getBody()));
        return $result;
    }

    /**
     * Get the response objects for all languages in TMDB
     *
     * @return Response
     * @throws GenericAPIException
     */
    public function getLanguages() : Response
    {
        $request = new Request('GET', $this->url . '/configuration/languages' . '?api_key=' . $this->key);
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        if (!$result->getHttpStatusCode() === 200) {
            throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        $result->setSuccessful();
        $result->setResponse(json_decode($response->getBody()));
        return $result;
    }

    /**
     * Get the response object for a tv show with the given id
     *
     * @param int $id
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function getTvShow(int $id) : Response
    {
        $request = new Request('GET', $this->url . '/tv/' . $id . '?api_key=' . $this->key . '&language=en-US');
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                throw new NoAPIResultsFoundException('TMDB - TV-Show: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

    /**
     * Get the response object for the external IDs of a tv show with the given id
     *
     * @param int $id
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function getTvShowIds(int $id) : Response
    {
        $request = new Request('GET', $this->url . '/tv/' . $id . '/external_ids?api_key=' . $this->key . '&language=en-US');
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                throw new NoAPIResultsFoundException('TMDB - TV-Show - IDs: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

    /**
     * Get the response object for a tv season of a tv show with the given id by its number
     *
     * @param int $id
     * @param int $number
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function getTvSeason(int $id, int $number) : Response
    {
        $request = new Request('GET', $this->url . '/tv/' . $id . '/season/' . $number . '?api_key=' . $this->key . '&language=en-US');
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                throw new NoAPIResultsFoundException('TMDB - TV-Season: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

    /**
     * Get the response object for the videos of a tv season belonging to a tv show with the given id by its number
     *
     * @param int $id
     * @param int $number
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function getTvSeasonVideos(int $id, int $number) : Response
    {
        $request = new Request('GET', $this->url . '/tv/' . $id . '/season/' . $number . '/videos?api_key=' . $this->key . '&language=en-US');
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody())->results);
                break;
            case 404:
                throw new NoAPIResultsFoundException('TMDB - TV-Season - Videos: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

    /**
     * Get the response object for the credits of a tv season belonging to a tv show with the given id by its number
     *
     * @param int $id
     * @param int $number
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function getTvSeasonCredits(int $id, int $number) : Response
    {
        $request = new Request('GET', $this->url . '/tv/' . $id . '/season/' . $number . '/credits?api_key=' . $this->key . '&language=en-US');
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                throw new NoAPIResultsFoundException('TMDB - TV-Season - Credits: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

}