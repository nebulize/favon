<?php

namespace App\Http\Responses\OMDB;

use App\Http\Responses\BaseResponse;

class OmdbResponse extends BaseResponse
{
    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $country;

    /**
     * @var float
     */
    private $imdb_score;

    /**
     * @var int
     */
    private $imdb_votes;

    /**
     * @var string[]
     */
    private $genres = [];

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
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return float|null
     */
    public function getImdbScore(): ?float
    {
        return $this->imdb_score;
    }

    /**
     * @return int|null
     */
    public function getImdbVotes(): ?int
    {
        return $this->imdb_votes;
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
    protected function parseResponse()
    {
        $this->summary = $this->parseProperty('Plot');
        $this->country = $this->parseProperty('Country');
        $this->imdb_score = $this->parseProperty('imdbRating', 'float');
        $this->imdb_votes = $this->parseProperty('imdbVotes', 'int');
        $this->parseGenres();
    }

    /**
     * Parse and set genres.
     */
    private function parseGenres()
    {
        if (isset($this->getResponse()->Genre) === true || $this->getResponse()->Genre !== '') {
            $this->genres = explode(', ', $this->getResponse()->Genre);
        }
    }

    /**
     * Get response object as associative array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
            'summary' => $this->getSummary(),
            'country' => $this->getCountry(),
            'imdb_score' => $this->getImdbScore(),
            'imdb_votes' => $this->getImdbVotes(),
        ];
    }
}
