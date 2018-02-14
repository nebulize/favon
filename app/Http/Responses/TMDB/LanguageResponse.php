<?php

namespace App\Http\Responses\TMDB;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\TMDB\Models\RLanguage;

class LanguageResponse extends BaseResponse
{
    /**
     * @var RLanguage[]
     */
    private $languages = array();

    /**
     * @return RLanguage[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse()
    {
        if (\is_array($this->getResponse())) {
            foreach ($this->getResponse() as $language) {
                $this->languages[] = new RLanguage($language);
            }
        }
    }
}