<?php

namespace App\Http\Clients;

use App\Http\Responses\TMDB\ChangedPersonsResponse;
use App\Http\Responses\TMDB\LanguageResponse;
use App\Http\Responses\TMDB\PersonResponse;
use App\Http\Responses\TMDB\TvSeasonCreditsResponse;
use App\Http\Responses\TMDB\TvSeasonResponse;
use App\Http\Responses\TMDB\TvSeasonVideosResponse;
use App\Http\Responses\TMDB\TvShowIdsResponse;
use App\Http\Responses\TMDB\TvShowResponse;
use GuzzleHttp\Psr7\Request;
use App\Http\Adapters\APIAdapter;
use App\Http\Adapters\TMDBAdapter;
use Illuminate\Contracts\Logging\Log;

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
     * @var Log
     */
    protected $logger;

    /**
     * TMDBClient constructor.
     * @param TMDBAdapter $adapter
     * @param Log $logger
     */
    public function __construct(TMDBAdapter $adapter, Log $logger)
    {
        $this->adapter = $adapter;
        $this->logger = $logger;
        $this->url = config('media.tmdb_url');
        $this->key = config('media.tmdb_api_key');
    }

    /**
     * Get the response object for all languages from TMDB.
     *
     * @return LanguageResponse
     */
    public function getLanguages(): LanguageResponse
    {
        $request = new Request('GET', $this->url.'/configuration/languages'.'?api_key='.$this->key);
        $response = $this->adapter->request($request);
        $result = new LanguageResponse((int) $response->getStatusCode());
        if ($result->getHttpStatusCode() === 200) {
            $result->setSuccessful();
            $result->setResponse(json_decode($response->getBody()));
        } else {
            $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for a person with the given id.
     *
     * @param int $id
     * @return PersonResponse
     */
    public function getPerson(int $id) : PersonResponse
    {
        $request = new Request('GET', $this->url.'/person/'.$id.'?api_key='.$this->key.'&language=en-US');
        $response = $this->adapter->request($request);
        $result = new PersonResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - Person: Could not find entry with ID '.$id);
                break;
            default:
                $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for a list of changed person entries.
     *
     * @param string|null $start_date
     * @param string|null $end_date
     * @param int $page
     * @return ChangedPersonsResponse
     */
    public function getChangedPersons(int $page = 1, string $start_date = null, string $end_date = null): ChangedPersonsResponse
    {
        $url = $this->url.'/person/changes?api_key='.$this->key.'&language=en-US';
        if ($start_date) {
            $url .= '&start_date='.$start_date;
        }
        if ($end_date) {
            $url .= '&end_date='.$end_date;
        }
        $url .= '&page='.$page;
        $request = new Request('GET', $url);
        $response = $this->adapter->request($request);
        $result = new ChangedPersonsResponse((int) $response->getStatusCode());
        if ($result->getHttpStatusCode() === 200) {
            $result->setSuccessful();
            $result->setResponse(json_decode($response->getBody()));
        } else {
            $this->logger->error('TMDB Languages '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for a tv show with the given id.
     *
     * @param int $id
     * @return TvShowResponse
     */
    public function getTvShow(int $id) : TvShowResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'?api_key='.$this->key.'&language=en-US');
        $response = $this->adapter->request($request);
        $result = new TvShowResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                $result->parseResponse();
                break;
            case 404:
                $this->logger->warning('TMDB - TVShow: Could not find entry with ID '.$id);
                break;
            default:
                $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for the external IDs of a tv show with the given id.
     *
     * @param int $id
     * @return TvShowIdsResponse
     */
    public function getTvShowIds(int $id) : TvShowIdsResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/external_ids?api_key='.$this->key.'&language=en-US');
        $response = $this->adapter->request($request);
        $result = new TvShowIdsResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - TVShow: Could not find entry with ID '.$id);
                break;
            default:
                $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for a tv season of a tv show with the given id by its number.
     *
     * @param int $id
     * @param int $number
     * @return TvSeasonResponse
     */
    public function getTvSeason(int $id, int $number) : TvSeasonResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/season/'.$number.'?api_key='.$this->key.'&language=en-US');
        $response = $this->adapter->request($request);
        $result = new TvSeasonResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - TVSeason: Could not find entry with ID '.$id.' and number '.$number);
                break;
            default:
                $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for the videos of a tv season belonging to a tv show with the given id by its number.
     *
     * @param int $id
     * @param int $number
     * @return TvSeasonVideosResponse
     */
    public function getTvSeasonVideos(int $id, int $number) : TvSeasonVideosResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/season/'.$number.'/videos?api_key='.$this->key.'&language=en-US');
        $response = $this->adapter->request($request);
        $result = new TvSeasonVideosResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody())->results);
                break;
            case 404:
                $this->logger->warning('TMDB - TV-Season - Videos: Could not find entry with ID '.$id);
                break;
            default:
                $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for the credits of a tv season belonging to a tv show with the given id by its number.
     *
     * @param int $id
     * @param int $number
     * @return TvSeasonCreditsResponse
     */
    public function getTvSeasonCredits(int $id, int $number) : TvSeasonCreditsResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/season/'.$number.'/credits?api_key='.$this->key.'&language=en-US');
        $response = $this->adapter->request($request);
        $result = new TvSeasonCreditsResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - TV-Season - Credits: Could not find entry with ID '.$id);
                break;
            default:
                $this->logger->error('TMDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }
}
