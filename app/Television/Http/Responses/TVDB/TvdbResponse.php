<?php

namespace Favon\Television\Http\Responses\TVDB;

use Favon\Application\Http\BaseResponse;

class TvdbResponse extends BaseResponse
{
    /**
     * @var string|null
     */
    protected $air_day;

    /**
     * @var string|null
     */
    protected $air_time;

    /**
     * @var string[]
     */
    protected $genres = [];

    /**
     * @return null|string
     */
    public function getAirDay(): ?string
    {
        return $this->air_day;
    }

    /**
     * @return null|string
     */
    public function getAirTime(): ?string
    {
        return $this->air_time;
    }

    /**
     * @return string[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * Parse the response object.
     */
    public function parseResponse(): void
    {
        $this->air_day = $this->parseProperty('airsDayOfWeek');
        $this->air_time = $this->parseProperty('airsTime');
        $this->parseGenres();
    }

    /**
     * Parse and set the genres.
     */
    private function parseGenres(): void
    {
        $tvdbGenres = config('favon.tvdb_genres');
        if (isset($this->getResponse()->genre) === true && \is_array($this->getResponse()->genre)) {
            foreach ($this->getResponse()->genre as $genre) {
                // If this genre is interesting to us, convert it to the equivalent name in our database
                if (isset($tmdbGenres[$genre])) {
                    $this->genres[] = $tvdbGenres[$genre];
                }
            }
        }
    }

    /**
     * Get response object as associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'air_day' => $this->getAirDay(),
            'air_time' => $this->getAirTime(),
        ];
    }
}
