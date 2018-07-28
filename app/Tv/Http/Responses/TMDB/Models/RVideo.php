<?php

namespace Favon\Tv\Http\Responses\TMDB\Models;

class RVideo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $type;

    /**
     * RVideo constructor.
     * @param \stdClass $object
     */
    public function __construct(\stdClass $object)
    {
        if (isset($object->name) && $object->name !== '') {
            $this->name = $object->name;
        }
        if (isset($object->key) && $object->key !== '') {
            $this->key = $object->key;
        }
        if (isset($object->type) && $object->type !== '') {
            $this->type = $object->type;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the response as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'key' => $this->getKey(),
            'type' => $this->getType(),
        ];
    }
}
