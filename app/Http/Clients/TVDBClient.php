<?php

namespace App\Http\Clients;

use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use App\Http\Adapters\APIAdapter;
use App\Http\Adapters\TVDBAdapter;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class TVDBClient
{
    protected const IDENTIFIER = 'tvdb';

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
    protected $token;

    /**
     * TVDBClient constructor.
     * @param TVDBAdapter $adapter
     */
    public function __construct(TVDBAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->url = config('media.tvdb_url');
        $this->authenticate();
    }

    /**
     * Authenticate with the TVDB API and request API token
     */
    protected function authenticate() : void
    {
        $client = new Client();
        $body = [
            'apikey' => config('media.tvdb_api_key'),
            'userkey' => config('media.tvdb_user_key'),
            'username' => config('media.tvdb_user_name')
        ];
        $response = $client->request('POST', $this->url . '/login', [
            'body' => json_encode($body),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody());
            $this->token = $responseBody->token;
        }
    }

    /**
     * Get the base request that can be used for all GET API calls
     *
     * @return Request
     */
    protected function getBaseRequest() : Request
    {
        if (!$this->token) {
            $this->authenticate();
        }
        return new Request('GET', $this->url, [
            'Authorization' => 'Bearer ' . $this->token
        ]);
    }

    /**
     * Get the response object for a TV show with the given IMDB ID
     *
     * @param int $id
     * @return Response
     * @throws GenericAPIException
     * @throws NoAPIResultsFoundException
     */
    public function get(int $id) : Response
    {
        $request = $this->getBaseRequest()->withUri(new Uri($this->url . '/series/' . $id));
        $response = $this->adapter->request($request);
        $result = new Response((int)$response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody())->data);
                break;
            case 404:
                throw new NoAPIResultsFoundException('TVDB: Could not find entry with ID '.$id);
                break;
            default:
                throw new GenericAPIException($response->getBody(), $response->getStatusCode());
        }
        return $result;
    }

//    public function getEpisodes(int $id, int $page = 1, array $episodes = array()) : array
//    {
//        $request = $this->getBaseRequest()->withUri(new Uri($this->url . '/series/' . $id . '/episodes?page=' . $page));
//        $response = $this->adapter->request($request);
//        $response = json_decode($response->getBody());
//        $episodes = array_merge($episodes, $response->data);
//        if ($response->links->next === null || $response->links->next === 'null')  {
//            return $episodes;
//        }
//        return $this->getEpisodes($id, $page + 1, $episodes);
//    }

    /**
     * Fetch all relevant data for a TV show from the TVDB API
     *
     * @param int $id
     * @return array
     * @throws NoAPIResultsFoundException
     * @throws ClientException
     */
    public function getTVDBData(int $id) : array
    {
        $result = $this->get($id);
        if (!$result->seriesName) {
            throw new NoAPIResultsFoundException('TVDB: no localized results found for show with id ' . $imdbId);
        }
        return [
            'imdb_id' => $result->imdbId,
            'name' => $result->seriesName,
            'status' => $result->status === 'Ended' ? 'Completed' : 'Continuing',
            'first_aired' => Carbon::parse($result->firstAired),
            'network' => $result->network,
            'runtime' => (int)$result->runtime,
            'rating' => $result->rating !== '' ? $result->rating : null,
            'plot' => $result->overview,
            'air_day' => $result->airsDayOfWeek !== '' ? $result->airsDayOfWeek : null,
            'air_time' => $result->airsTime !== '' ? $result->airsTime : null,
            'tvdb_id' => $result->id,
        ];
    }

}