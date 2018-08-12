<?php

namespace Favon\Television\Http\Responses\Tmdb;

use Favon\Application\Http\BaseResponse;
use Favon\Television\Http\Responses\Tmdb\Models\RCredit;

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
    protected function parseResponse(): void
    {
        foreach ($this->getResponse()->cast as $credit) {
            $this->results[] = new RCredit($credit, 'cast');
        }
        foreach ($this->getResponse()->crew as $credit) {
            $this->results[] = new RCredit($credit, 'crew');
        }
    }
}
