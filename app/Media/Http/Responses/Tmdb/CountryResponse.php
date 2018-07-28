<?php

namespace Favon\Media\Http\Responses\TMDB;

use Favon\Application\Abstracts\BaseResponse;
use Favon\Media\Http\Responses\Tmdb\Models\RCountry;

class CountryResponse extends BaseResponse
{
    /**
     * @var RCountry[]
     */
    private $countries = [];

    /**
     * @return RCountry[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse(): void
    {
        if (\is_array($this->getResponse())) {
            foreach ($this->getResponse() as $country) {
                $this->countries[] = new RCountry($country);
            }
        }
    }
}
