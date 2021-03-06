<?php

namespace Favon\Media\Http\Responses\TMDB;

use Favon\Application\Http\BaseResponse;
use Favon\Media\Http\Responses\Tmdb\Models\RLanguage;

class LanguageResponse extends BaseResponse
{
    /**
     * @var RLanguage[]
     */
    private $languages = [];

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
