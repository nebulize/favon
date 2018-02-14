<?php

namespace App\Http\Responses\TMDB\Models;

class RLanguage
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * RLanguage constructor.
     * @param \stdClass $object
     */
    public function __construct(\stdClass $object)
    {
        if (isset($object->iso_639_1) && $object->iso_639_1 !== '') {
            $this->code = $object->iso_639_1;
        }
        if (isset($object->english_name) && $object->english_name !== '') {
            $this->name = $object->english_name;
        }
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get response object as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
        ];
    }
}
