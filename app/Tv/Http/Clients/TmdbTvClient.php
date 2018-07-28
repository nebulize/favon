<?php

namespace Favon\Tv\Http\Clients;

use Favon\Media\Http\Gateways\TmdbGateway;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Favon\Tv\Http\Responses\Tmdb\TvShowResponse;
use Favon\Tv\Http\Responses\Tmdb\TvSeasonResponse;
use Favon\Tv\Http\Responses\Tmdb\TvShowIdsResponse;
use Favon\Tv\Http\Responses\Tmdb\ChangedTvShowsResponse;
use Favon\Tv\Http\Responses\Tmdb\TvSeasonVideosResponse;
use Favon\Tv\Http\Responses\Tmdb\TvSeasonCreditsResponse;

class TmdbTvClient
{
    /**
     * @var TmdbGateway
     */
    protected $gateway;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TmdbTvClient constructor.
     * @param TmdbGateway $gateway
     * @param LoggerInterface $logger
     */
    public function __construct(TmdbGateway $gateway, LoggerInterface $logger)
    {
        $this->gateway = $gateway;
        $this->logger = $logger;
        $this->url = config('favon.tmdb_url');
        $this->key = config('favon.tmdb_api_key');
    }

    /**
     * Get the response object for a tv show with the given id.
     *
     * @param int $id
     *
     * @return TvShowResponse
     */
    public function getTvShow(int $id): TvShowResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'?api_key='.$this->key.'&language=en-US');
        $response = $this->gateway->request($request);
        $result = new TvShowResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - TvShow: Could not find entry with ID '.$id);
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
     *
     * @return ChangedTvShowsResponse
     */
    public function getChangedTvShows(int $page = 1, string $start_date = null, string $end_date = null): ChangedTvShowsResponse
    {
        $url = $this->url.'/tv/changes?api_key='.$this->key.'&language=en-US';
        if ($start_date) {
            $url .= '&start_date='.$start_date;
        }
        if ($end_date) {
            $url .= '&end_date='.$end_date;
        }
        $url .= '&page='.$page;
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new ChangedTvShowsResponse((int) $response->getStatusCode());
        if ($result->getHttpStatusCode() === 200) {
            $result->setSuccessful();
            $result->setResponse(json_decode($response->getBody()));
        } else {
            $this->logger->error('TMDB Changed TV Shows '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }

    /**
     * Get the response object for the external IDs of a tv show with the given id.
     *
     * @param int $id
     *
     * @return TvShowIdsResponse
     */
    public function getTvShowIds(int $id): TvShowIdsResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/external_ids?api_key='.$this->key.'&language=en-US');
        $response = $this->gateway->request($request);
        $result = new TvShowIdsResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - TvShow: Could not find entry with ID '.$id);
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
     *
     * @return TvSeasonResponse
     */
    public function getTvSeason(int $id, int $number): TvSeasonResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/season/'.$number.'?api_key='.$this->key.'&language=en-US');
        $response = $this->gateway->request($request);
        $result = new TvSeasonResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
                break;
            case 404:
                $this->logger->warning('TMDB - TvSeason: Could not find entry with ID '.$id.' and number '.$number);
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
     *
     * @return TvSeasonVideosResponse
     */
    public function getTvSeasonVideos(int $id, int $number): TvSeasonVideosResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/season/'.$number.'/videos?api_key='.$this->key.'&language=en-US');
        $response = $this->gateway->request($request);
        $result = new TvSeasonVideosResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody()));
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
     *
     * @return TvSeasonCreditsResponse
     */
    public function getTvSeasonCredits(int $id, int $number): TvSeasonCreditsResponse
    {
        $request = new Request('GET', $this->url.'/tv/'.$id.'/season/'.$number.'/credits?api_key='.$this->key.'&language=en-US');
        $response = $this->gateway->request($request);
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
