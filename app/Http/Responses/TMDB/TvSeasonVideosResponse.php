<?php

namespace App\Http\Responses\TMDB;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\TMDB\Models\RVideo;

class TvSeasonVideosResponse extends BaseResponse
{
    /**
     * @var RVideo[]
     */
    private $results = array();

    /**
     * @return RVideo[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse(): void
    {
        foreach ($this->getResponse()->results as $result) {
            if (isset($result->site) && $result->site === 'Youtube') {
                $this->results[] = new RVideo($result);
            }
        }
    }
}