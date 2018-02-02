<?php

namespace App\Http\Adapters;

use bandwidthThrottle\tokenBucket\BlockingConsumer;
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\storage\FileStorage;
use bandwidthThrottle\tokenBucket\TokenBucket;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

/**
 * API adapter for limiting outgoing API requests
 * Class APIAdapter
 * @package App\Http\Adapters
 */
class APIAdapter
{
    /**
     * @var BlockingConsumer
     */
    protected $consumer;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $type;

    /**
     * APIAdapter constructor.
     * @param int $limit
     * @param string $unit
     * @param string $type
     */
    public function __construct(int $limit, string $unit, string $type)
    {
        $this->type = $type;
        $storage = new FileStorage(storage_path() . '/api/' . $type . '.bucket');
        $rate = new Rate($limit, $unit);
        $bucket = new TokenBucket($limit, $rate, $storage);
        $this->consumer = new BlockingConsumer($bucket);
        $bucket->bootstrap($limit);
        $this->client = new Client();
    }

    /**
     * Request a resource
     *
     * @param Request $request
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws ClientException
     */
    public function request(Request $request)
    {
        $this->consumer->consume(1);
        \Log::info('API Request: ' . $request->getRequestTarget());
        return $this->client->send($request, ['http_errors' => false]);
    }

    /**
     * Delete the token bucket
     */
    public function close() : void
    {
        unlink(storage_path() . '/api/' . $this->type . '.bucket');
    }

}