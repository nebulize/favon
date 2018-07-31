<?php

namespace Favon\Media\Http\Clients;

use Favon\Application\Http\BaseResponse;
use Favon\Media\Http\Gateways\TmdbGateway;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class TmdbClient
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
     * Set API response and log errors.
     *
     * @param BaseResponse $response
     * @param ResponseInterface $httpResponse
     * @param string $url
     */
    protected function setResponse(BaseResponse $response, ResponseInterface $httpResponse, string $url): void
    {
        switch ($response->getHttpStatusCode()) {
            case 200:
                $response->setSuccessful();
                $response->setResponse(json_decode($httpResponse->getBody()));
                break;
            case 404:
                $this->logger->warning('404 - No results: '.$url);
                break;
            default:
                $this->logger->error('TMDB '.$httpResponse->getStatusCode().': '.$httpResponse->getBody());
        }
    }
}
