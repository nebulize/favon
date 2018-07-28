<?php

namespace Favon\Application\Abstracts;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\storage\FileStorage;

/**
 * API gateway for limiting outgoing API requests
 * Class Gateway.
 */
abstract class Gateway
{
    public const IDENTIFIER = 'general';

    /**
     * @var BlockingConsumer
     */
    protected $consumer;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Gateway constructor.
     *
     * @param int $limit
     * @param string $unit
     */
    public function __construct(int $limit, string $unit)
    {
        $storage = new FileStorage(storage_path().'/api/'.self::IDENTIFIER.'.bucket');
        $rate = new Rate($limit, $unit);
        $bucket = new TokenBucket($limit, $rate, $storage);
        $this->consumer = new BlockingConsumer($bucket);
        $bucket->bootstrap($limit);
        $this->client = new Client();
    }

    /**
     * Request an API resource.
     *
     * @param Request $request
     * @param int $tries
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request(Request $request, int $tries = 0)
    {
        $this->consumer->consume(1);

        $response = $this->client->send($request, ['http_errors' => false]);
        if ($tries < 15 && (
            $response->getStatusCode() === 502
            || $response->getStatusCode() === 522
            || $response->getStatusCode() === 408
            || $response->getStatusCode() === 524
            )) {
            sleep(3);

            return $this->request($request, $tries + 1);
        }

        return $response;
    }

    /**
     * Delete the token bucket.
     */
    public function close(): void
    {
        unlink(storage_path().'/api/'.self::IDENTIFIER.'.bucket');
    }
}
