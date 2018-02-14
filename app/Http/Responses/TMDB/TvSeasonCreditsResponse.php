<?php

namespace App\Http\Responses\TMDB;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\TMDB\Models\RCredit;

class TvSeasonCreditsResponse extends BaseResponse
{
    /**
     * @var RCredit[]
     */
    private $results = [];

    /**
     * @return RCredit[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse()
    {
        foreach ($this->getResponse()->cast as $credit) {
            $this->results[] = new RCredit($credit, 'cast');
        }
        foreach ($this->getResponse()->crew as $credit) {
            $this->results[] = new RCredit($credit, 'crew');
        }
    }
}
