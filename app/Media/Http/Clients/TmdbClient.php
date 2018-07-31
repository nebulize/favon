<?php

namespace Favon\Media\Http\Clients;

use Favon\Application\Http\BaseResponse;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Favon\Media\Http\Gateways\TmdbGateway;
use Favon\Media\Http\Responses\TMDB\PersonResponse;
use Favon\Media\Http\Responses\TMDB\CountryResponse;
use Favon\Media\Http\Responses\TMDB\LanguageResponse;
use Favon\Media\Http\Responses\TMDB\ChangedPersonsResponse;

class TmdbClient
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
     * Get the response object for all languages from TMDB.
     *
     * @return LanguageResponse
     */
    public function getLanguages(): LanguageResponse
    {
        $url = $this->url.'/configuration/languages'.'?api_key='.$this->key;
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new LanguageResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

        return $result;
    }

    /**
     * Get the response object for all languages from TMDB.
     *
     * @return CountryResponse
     */
    public function getCountries(): CountryResponse
    {
        $url = $this->url.'/configuration/countries'.'?api_key='.$this->key;
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new CountryResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

        return $result;
    }

    /**
     * Get the response object for a person with the given id.
     *
     * @param int $id
     *
     * @return PersonResponse
     */
    public function getPerson(int $id) : PersonResponse
    {
        $url = $this->url.'/person/'.$id.'?api_key='.$this->key.'&language=en-US';
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new PersonResponse((int) $response->getStatusCode());
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
     * @return ChangedPersonsResponse
     */
    public function getChangedPersons(int $page = 1, string $start_date = null, string $end_date = null): ChangedPersonsResponse
    {
        $url = $this->url.'/person/changes?api_key='.$this->key.'&language=en-US&page='.$page;
        if ($start_date) {
            $url .= '&start_date='.$start_date;
        }
        if ($end_date) {
            $url .= '&end_date='.$end_date;
        }
        $request = new Request('GET', $url);
        $response = $this->gateway->request($request);
        $result = new ChangedPersonsResponse((int) $response->getStatusCode());
        $this->setResponse($result, $response, $url);

        return $result;
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
