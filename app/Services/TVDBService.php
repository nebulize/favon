<?php

namespace App\Services;

use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use App\Http\Clients\TVDBClient;
use Illuminate\Contracts\Logging\Log;

class TVDBService
{
    /**
     * @var TVDBClient
     */
    protected $client;

    /**
     * @var Log
     */
    protected $logger;

    /**
     * TVDBService constructor.
     * @param TVDBClient $client
     * @param Log $logger
     */
    public function __construct(TVDBClient $client, Log $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Get all application relevant data from a TVDB entry by TVDB id
     *
     * @param int $id
     * @return array
     */
    public function get(int $id) : array
    {
        try {
            $response = $this->client->get($id);
        } catch (NoAPIResultsFoundException $e) {
            $this->logger->warning('Skipped ' . $id . "\n" . $e->getMessage());
            return null;
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }

        return [
            'air_day' => $response->getResponse()->airsDayOfWeek !== '' ? $response->getResponse()->airsDayOfWeek : null,
            'air_time' => $response->getResponse()->airsTime !== '' ? $response->getResponse()->airsTime : null,
        ];
    }

}