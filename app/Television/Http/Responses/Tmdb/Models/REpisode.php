<?php

namespace Favon\Television\Http\Responses\TMDB\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class REpisode
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
    private $plot;

    /**
     * @var int
     */
    private $tmdb_id;

    /**
     * REpisode constructor.
     * @param \stdClass $object
     */
    public function __construct(\stdClass $object)
    {
        if (isset($object->episode_number) && $object->episode_number !== '') {
            $this->number = (int) $object->episode_number;
        } else {
            $this->number = 0;
        }
        if (isset($object->air_date) && $object->air_date !== '') {
            $this->parseFirstAired($object->air_date);
        }
        if (isset($object->name) && $object->name !== '') {
            $this->name = $object->name;
        }
        if (isset($object->overview) && $object->overview !== '') {
            $this->plot = $object->overview;
        }
        if (isset($object->id) && $object->id !== '') {
            $this->tmdb_id = (int) $object->id;
        }
    }

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
    public function getPlot(): ?string
    {
        return $this->plot;
    }

    /**
     * @return int|null
     */
    public function getTmdbId(): ?int
    {
        return $this->tmdb_id;
    }

    /**
     * Parse and set the first aired date.
     *
     * @param $date
     */
    private function parseFirstAired($date): void
    {
        try {
            $this->first_aired = Carbon::parse($date);
        } catch (\Exception $e) {
            Log::warning('Could not parse air_date for TMDB episode.');
        }
    }

    /**
     * Get attributes as associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return  [
            'number' => $this->getNumber(),
            'first_aired' => $this->getFirstAired(),
            'name' => $this->getName(),
            'plot' => $this->getPlot(),
            'tmdb_id' => $this->getTmdbId(),
        ];
    }
}
