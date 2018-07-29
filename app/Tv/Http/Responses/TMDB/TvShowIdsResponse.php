<?php

namespace Favon\Tv\Http\Responses\Tmdb;

use Favon\Application\Http\BaseResponse;

class TvShowIdsResponse extends BaseResponse
{
    /**
     * @var int
     */
    protected $tvdb_id;

    /**
     * @var string
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
     * @return string|null
     */
    public function getImdbId(): ?string
    {
        return $this->imdb_id;
    }

    /**
     * Parse the response.
     */
    protected function parseResponse(): void
    {
        $this->tvdb_id = $this->parseProperty('tvdb_id', BaseResponse::TYPE_INT);
        $this->imdb_id = $this->parseProperty('imdb_id');
    }

    /**
     * Get response object as associative array.
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
