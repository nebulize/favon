<?php

namespace Favon\Television\Http\Responses\TMDB\Models;

class RSeason
{
    /**
     * @var int
     */
    private $number;

    /**
     * RSeason constructor.
     * @param \stdClass $object
     */
    public function __construct(\stdClass $object)
    {
        if (isset($object->season_number) && $object->season_number !== '') {
            $this->number = (int) $object->season_number;
        }
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }
}
