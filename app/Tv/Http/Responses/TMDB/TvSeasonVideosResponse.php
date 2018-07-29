<?php

namespace Favon\Tv\Http\Responses\Tmdb;

use Favon\Application\Http\BaseResponse;
use Favon\Tv\Http\Responses\TMDB\Models\RVideo;

class TvSeasonVideosResponse extends BaseResponse
{
    /**
     * @var RVideo[]
     */
    private $results = [];

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
            if (isset($result->site) && $result->site === 'YouTube') {
                $this->results[] = new RVideo($result);
            }
        }
    }
}
