<?php

namespace App\Http\Responses\TVDB;

use App\Http\Responses\BaseResponse;

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
     * Parse the response object
     */
    public function parseResponse(): void
    {
        $this->air_day = $this->parseProperty('airsDayOfWeek');
        $this->air_time = $this->parseProperty('airsTime');
    }

    /**
     * Get response object as associative array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'air_day' => $this->getAirDay(),
            'air_time' => $this->getAirTime()
        ];
    }
}