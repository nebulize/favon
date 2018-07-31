<?php

namespace Favon\Television\Http\Clients;

use Favon\Media\Http\Clients\TmdbClient;
use GuzzleHttp\Psr7\Request;
use Favon\Television\Http\Responses\Tmdb\TvShowResponse;
use Favon\Television\Http\Responses\Tmdb\TvSeasonResponse;
use Favon\Television\Http\Responses\Tmdb\TvShowIdsResponse;
use Favon\Television\Http\Responses\Tmdb\ChangedTvShowsResponse;
use Favon\Television\Http\Responses\Tmdb\TvSeasonVideosResponse;
use Favon\Television\Http\Responses\Tmdb\TvSeasonCreditsResponse;

class TmdbTvClient extends TmdbClient
{
    /**
     * Get the response object for a tv show with the given id.
     *
     * @param int $id
     *
     * @return TvShowResponse
     */
    public function getTvShow(int $id): TvShowResponse
    {
        $url = $this->url.'/tv/'.$id.'?api_key='.$this->key.'&language=en-US';
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new TvShowResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

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
        $this->setResponse($result, $response, $url);

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
        $url = $this->url.'/tv/'.$id.'/external_ids?api_key='.$this->key.'&language=en-US';
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new TvShowIdsResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

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
        $url = $this->url.'/tv/'.$id.'/season/'.$number.'?api_key='.$this->key.'&language=en-US';
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new TvSeasonResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

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
        $url = $this->url.'/tv/'.$id.'/season/'.$number.'/videos?api_key='.$this->key.'&language=en-US';
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new TvSeasonVideosResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

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
        $url = $this->url.'/tv/'.$id.'/season/'.$number.'/credits?api_key='.$this->key.'&language=en-US';
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new TvSeasonCreditsResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

        return $result;
    }
}
