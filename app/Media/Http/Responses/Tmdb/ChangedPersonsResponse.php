<?php

namespace Favon\Media\Http\Responses\Tmdb;

use Favon\Application\Http\BaseResponse;

class ChangedPersonsResponse extends BaseResponse
{
    /**
     * @var \stdClass[]
     */
    private $results = [];

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $total_pages;

    /**
     * @var int
     */
    private $total_results;

    /**
     * @return \stdClass[]
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @return int|null
     */
    public function getTotalPages(): ?int
    {
        return $this->total_pages;
    }

    /**
     * @return int|null
     */
    public function getTotalResults(): ?int
    {
        return $this->total_results;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse(): void
    {
        $this->results = $this->getResponse()->results;
        $this->page = (int) $this->getResponse()->page;
        $this->total_pages = (int) $this->getResponse()->total_pages;
        $this->total_results = (int) $this->getResponse()->total_results;
    }
}
