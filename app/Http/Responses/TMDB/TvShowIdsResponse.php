<?php

namespace App\Http\Responses\TMDB;

use App\Http\Responses\BaseResponse;

class TvShowIdsResponse extends BaseResponse
{
    /**
     * @var int
     */
    protected $tvdb_id;

    /**
     * @var int
     */
    protected $imdb_id;

    /**
     * @return int|null
     */
    public function getTvdbId(): ?int
    {
        return $this->tvdb_id;
    }

    /**
     * @return int|null
     */
    public function getImdbId(): ?int
    {
        return $this->imdb_id;
    }

    /**
     * Parse the response.
     */
    protected function parseResponse(): void
    {
        $this->tvdb_id = $this->parseProperty('tvdb_id', BaseResponse::TYPE_INT);
        $this->imdb_id = $this->parseProperty('imdb_id', BaseResponse::TYPE_INT);
    }

    /**
     * Get response object as associative array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'tvdb_id' => $this->getTvdbId(),
            'imdb_id' => $this->getImdbId(),
        ];
    }
}