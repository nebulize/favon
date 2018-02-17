<?php

namespace App\Http\Responses\TMDB;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\TMDB\Models\CountryListItem;

class CountryResponse extends BaseResponse
{
    /**
     * @var CountryListItem[]
     */
    private $countries = [];

    /**
     * @return CountryListItem[]
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
                $this->countries[] = new CountryListItem($country);
            }
        }
    }
}
