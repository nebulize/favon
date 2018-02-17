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
    private $countries;

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
    public function getCountries(): ?string
    {
        return $this->countries;
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
    protected function parseResponse(): void
    {
        $this->summary = $this->parseProperty('Plot');
        $this->parseCountries();
        $this->imdb_score = $this->parseProperty('imdbRating', BaseResponse::TYPE_FLOAT);
        $this->imdb_votes = $this->parseProperty('imdbVotes', BaseResponse::TYPE_INT);
        $this->parseGenres();
    }

    /**
     * Parse and set genres.
     */
    private function parseGenres(): void
    {
        if (isset($this->getResponse()->Genre) === true && $this->getResponse()->Genre !== '') {
            $this->genres = explode(', ', $this->getResponse()->Genre);
        }
    }

    /**
     * Parse and set countries.
     */
    private function parseCountries(): void
    {
        if (isset($this->getResponse()->Country) === true && $this->getResponse()->Country !== '') {
            $this->countries = explode(', ', $this->getResponse()->Country);
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
            'imdb_score' => $this->getImdbScore(),
            'imdb_votes' => $this->getImdbVotes(),
        ];
    }
}
