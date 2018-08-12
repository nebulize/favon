<?php

namespace Favon\Television\Http\Responses\Tmdb;

use Carbon\Carbon;
use Favon\Application\Http\BaseResponse;
use Favon\Television\Http\Responses\Tmdb\Models\REpisode;

class TvSeasonResponse extends BaseResponse
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var Carbon
     */
    private $first_aired;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $poster;

    /**
     * @var int
     */
    private $tmdb_id;

    /**
     * @var REpisode[]
     */
    private $episodes = [];

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @return Carbon|null
     */
    public function getFirstAired(): ?Carbon
    {
        return $this->first_aired;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @return string|null
     */
    public function getPoster(): ?string
    {
        return $this->poster;
    }

    /**
     * @return int|null
     */
    public function getTmdbId(): ?int
    {
        return $this->tmdb_id;
    }

    /**
     * @return REpisode[]
     */
    public function getEpisodes(): array
    {
        return $this->episodes;
    }

    /**
     * Parse response object.
     */
    protected function parseResponse(): void
    {
        $this->number = $this->parseProperty('season_number', BaseResponse::TYPE_INT);
        $this->name = $this->parseProperty('name');
        $this->summary = $this->parseProperty('overview');
        $this->poster = $this->parseProperty('poster_path');
        $this->tmdb_id = $this->parseProperty('id', BaseResponse::TYPE_INT);
        $this->parseFirstAired();
        $this->parseEpisodes();
    }

    /**
     * Parse and set first aired date.
     */
    private function parseFirstAired(): void
    {
        $first_aired = $this->parseProperty('air_date');
        if ($first_aired === null) {
            return;
        }
        try {
            $this->first_aired = Carbon::parse($this->getResponse()->air_date);
        } catch (\Exception $e) {
            Log::warning('Could not parse air_date for TMDB'.$this->getResponse()->id);
        }
    }

    /**
     * Parse and set the episodes.
     */
    private function parseEpisodes(): void
    {
        if (isset($this->getResponse()->episodes) === true && \is_array($this->getResponse()->episodes)) {
            foreach ($this->getResponse()->episodes as $episode) {
                $this->episodes[] = new REpisode($episode);
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
            'number' => $this->getNumber(),
            'first_aired' => $this->getFirstAired(),
            'name' => $this->getName(),
            'summary' => $this->getSummary(),
            'poster' => $this->getPoster(),
            'tmdb_id' => $this->getTmdbId(),
        ];
    }
}
