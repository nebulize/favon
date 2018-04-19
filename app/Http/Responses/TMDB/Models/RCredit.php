<?php

namespace App\Http\Responses\TMDB\Models;

class RCredit
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $character;

    /**
     * @var string
     */
    private $job;

    /**
     * @var int
     */
    private $order;

    /**
     * @var int
     */
    private $tmdb_person_id;

    public function __construct(\stdClass $object, $role)
    {
        $this->role = $role;
        if (isset($object->character) && $object->character !== '') {
            $this->character = $object->character;
        }
        if (isset($object->job) && $object->job !== '') {
            $this->job = $object->job;
        }
        if (isset($object->order) && $object->order !== '') {
            $this->order = $object->order;
        }
        $this->tmdb_person_id = $object->id;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getCharacter(): ?string
    {
        return $this->character;
    }

    /**
     * @return string|null
     */
    public function getJob(): ?string
    {
        return $this->job;
    }

    /**
     * @return int|null
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }

    /**
     * @return int|null
     */
    public function getTmdbPersonId(): ?int
    {
        return $this->tmdb_person_id;
    }

    /**
     * Get the response as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'role' => $this->getRole(),
            'job' => $this->getJob(),
            'character' => $this->getCharacter(),
            'order' => $this->getOrder(),
        ];
    }
}
