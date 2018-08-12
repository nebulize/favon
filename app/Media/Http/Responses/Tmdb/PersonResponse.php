<?php

namespace Favon\Media\Http\Responses\Tmdb;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Favon\Application\Http\BaseResponse;

class PersonResponse extends BaseResponse
{
    /**
     * @var Carbon
     */
    private $birthday;

    /**
     * @var Carbon
     */
    private $deathday;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var string
     */
    private $biography;

    /**
     * @var string
     */
    private $place_of_birth;

    /**
     * @var string
     */
    private $photo;

    /**
     * @var int
     */
    private $tmdb_id;

    /**
     * @return Carbon|null
     */
    public function getBirthday(): ?Carbon
    {
        return $this->birthday;
    }

    /**
     * @return Carbon|null
     */
    public function getDeathday(): ?Carbon
    {
        return $this->deathday;
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
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return string|null
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * @return string|null
     */
    public function getPlaceOfBirth(): ?string
    {
        return $this->place_of_birth;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @return int|null
     */
    public function getTmdbId(): ?int
    {
        return $this->tmdb_id;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse(): void
    {
        $this->parseBirthday();
        $this->parseDeathday();
        $this->name = $this->parseProperty('name');
        $this->parseGender();
        $this->biography = $this->parseProperty('biography');
        $this->place_of_birth = $this->parseProperty('place_of_birth');
        $this->photo = $this->parseProperty('profile_path');
        $this->tmdb_id = $this->parseProperty('id', BaseResponse::TYPE_INT);
    }

    /**
     * Parse and set the birthday.
     */
    private function parseBirthday(): void
    {
        $birthday = $this->parseProperty('birthday');
        if ($birthday === null) {
            return;
        }
        try {
            $this->birthday = Carbon::parse($this->getResponse()->birthday);
        } catch (\Exception $e) {
            Log::warning('Could not parse birthday for TMDB person '.$this->getResponse()->id);
        }
    }

    /**
     * Parse and set the deathday.
     */
    private function parseDeathday(): void
    {
        $deathday = $this->parseProperty('deathday');
        if ($deathday === null) {
            return;
        }
        try {
            $this->deathday = Carbon::parse($this->getResponse()->deathday);
        } catch (\Exception $e) {
            Log::warning('Could not parse deathday for TMDB person '.$this->getResponse()->id);
        }
    }

    /**
     * Parse and set the gender.
     */
    private function parseGender(): void
    {
        $gender = $this->parseProperty('gender', BaseResponse::TYPE_INT);
        if ($gender === 1) {
            $this->gender = 'female';
        } elseif ($gender === 2) {
            $this->gender = 'male';
        }
    }

    /**
     * Get response object as an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'birthday' => $this->getBirthday(),
            'deathday' => $this->getDeathday(),
            'name' => $this->getName(),
            'gender' => $this->getGender(),
            'biography' => $this->getBiography(),
            'place_of_birth' => $this->getPlaceOfBirth(),
            'photo' => $this->getPhoto(),
            'tmdb_id' => $this->getTmdbId(),
        ];
    }
}
