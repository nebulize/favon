<?php

namespace Favon\Television\Http\Clients;

use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Favon\Media\Http\Gateways\OmdbGateway;
use Favon\Television\Http\Responses\OMDB\OmdbResponse;

class OmdbTvClient
{
    /**
     * API Client Identifier.
     */
    protected const IDENTIFIER = 'omdb';

    /**
     * @var OmdbGateway
     */
    protected $gateway;

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
     * OmdbTvClient constructor.
     * @param OmdbGateway $gateway
     * @param LoggerInterface $logger
     */
    public function __construct(OmdbGateway $gateway, LoggerInterface $logger)
    {
        $this->gateway = $gateway;
        $this->url = config('favon.omdb_url').'/?apikey='.config('favon.omdb_api_key');
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
        $response = $this->gateway->request($request);
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
                $this->logger->warning($response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }
}
