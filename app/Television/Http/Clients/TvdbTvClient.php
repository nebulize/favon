<?php

namespace Favon\Television\Http\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Predis\Client as RedisClient;
use Favon\Media\Http\Gateways\TvdbGateway;
use Favon\Television\Http\Responses\TVDB\TvdbResponse;

class TvdbTvClient
{
    protected const IDENTIFIER = 'tvdb';

    /**
     * @var TvdbGateway
     */
    protected $gateway;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RedisClient
     */
    protected $redis;

    /**
     * TVDBClient constructor.
     * @param TvdbGateway $gateway
     * @param LoggerInterface $logger
     */
    public function __construct(TvdbGateway $gateway, LoggerInterface $logger)
    {
        $this->gateway = $gateway;
        $this->url = config('favon.tvdb_url');
        $this->logger = $logger;
        $this->redis = new RedisClient([
            'host' => config('database.redis.default.host'),
            'port' => config('database.redis.default.port'),
        ]);
        $this->loadToken();
    }

    /**
     * Load the TVDB from redis.
     */
    protected function loadToken(): void
    {
        // Parameters passed using a named array:
        if ((bool) $this->redis->exists('tvdb-token') === false) {
            $this->authenticate();
        }
        $this->token = $this->redis->get('tvdb-token');
    }

    /**
     * Authenticate with the TVDB API and request API token.
     */
    protected function authenticate(): void
    {
        $this->logger->info('Authenticating TVDB');
        $client = new Client();
        $body = [
            'apikey' => config('favon.tvdb_api_key'),
            'userkey' => config('favon.tvdb_user_key'),
            'username' => config('favon.tvdb_user_name'),
        ];
        $response = $client->request('POST', $this->url.'/login', [
            'body' => json_encode($body),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            $responseBody = json_decode($response->getBody());
            $this->token = $responseBody->token;
            $this->redis->set('tvdb-token', $this->token);
            // Expire token after 24 hours
            $this->redis->expire('tvdb-token', 86400);
        }
    }

    /**
     * Get the base request that can be used for all GET API calls.
     *
     * @return Request
     */
    protected function getBaseRequest(): Request
    {
        if (! $this->token) {
            $this->authenticate();
        }

        return new Request('GET', $this->url, [
            'Authorization' => 'Bearer '.$this->token,
        ]);
    }

    /**
     * Get the response object for a TV show with the given IMDB ID.
     *
     * @param int $id
     * @return TvdbResponse
     */
    public function get(int $id): TvdbResponse
    {
        $request = $this->getBaseRequest()->withUri(new Uri($this->url.'/series/'.$id));
        $response = $this->gateway->request($request);
        $result = new TvdbResponse((int) $response->getStatusCode());
        switch ($result->getHttpStatusCode()) {
            case 200:
                $result->setSuccessful();
                $result->setResponse(json_decode($response->getBody())->data);
                break;
            case 401:
                $this->authenticate();

                return $this->get($id);
            case 404:
                $this->logger->warning('TVDB: Could not find entry with ID '.$id);
                break;
            default:
                $this->logger->warning('TVDB '.$response->getStatusCode().': '.$response->getBody());
        }

        return $result;
    }
}
