<?php

namespace Favon\Television\Http\Responses\Tmdb;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Favon\Application\Http\BaseResponse;
use Favon\Television\Http\Responses\Tmdb\Models\RSeason;

class TvShowResponse extends BaseResponse
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Carbon
     */
    protected $first_aired;

    /**
     * @var string
     */
    protected $runtime;

    /**
     * @var string
     */
    protected $plot;

    /**
     * @var string
     */
    protected $poster;

    /**
     * @var string
     */
    protected $banner;

    /**
     * @var string
     */
    protected $homepage;

    /**
     * @var int
     */
    protected $tmdb_id;

    /**
     * @var float
     */
    protected $popularity;

    /**
     * @var string[]
     */
    protected $networks = [];

    /**
     * @var string[]
     */
    protected $languages = [];

    /**
     * @var string[]
     */
    protected $countries = [];

    /**
     * @var RSeason[]
     */
    protected $seasons = [];

    /**
     * @var string[]
     */
    protected $genres = [];

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
    public function getStatus(): ?string
    {
        return $this->status;
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
    public function getRuntime(): ?string
    {
        return $this->runtime;
    }

    /**
     * @return string|null
     */
    public function getPlot(): ?string
    {
        return $this->plot;
    }

    /**
     * @return string|null
     */
    public function getPoster(): ?string
    {
        return $this->poster;
    }

    /**
     * @return string|null
     */
    public function getBanner(): ?string
    {
        return $this->banner;
    }

    /**
     * @return string|null
     */
    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    /**
     * @return int|null
     */
    public function getTmdbId(): ?int
    {
        return $this->tmdb_id;
    }

    /**
     * @return float|null
     */
    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    /**
     * @return string[]
     */
    public function getNetworks(): array
    {
        return $this->networks;
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @return string[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return RSeason[]
     */
    public function getSeasons(): array
    {
        return $this->seasons;
    }

    /**
     * @return string[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * Parse the response object.
     */
    protected function parseResponse(): void
    {
        $this->parseName();
        $this->parseStatus();
        $this->parseFirstAired();
        $this->parseNetworks();
        $this->parseRuntime();
        $this->plot = $this->parseProperty('overview');
        $this->poster = $this->parseProperty('poster_path');
        $this->banner = $this->parseProperty('backdrop_path');
        $this->homepage = $this->parseProperty('homepage');
        $this->tmdb_id = $this->parseProperty('id', BaseResponse::TYPE_INT);
        $this->popularity = $this->parseProperty('popularity', BaseResponse::TYPE_FLOAT);
        $this->parseLanguages();
        $this->parseCountries();
        $this->parseSeasons();
        $this->parseGenres();
    }

    /**
     * Parse and set the name.
     */
    private function parseName(): void
    {
        $name = $this->parseProperty('name');
        if ($name === null) {
            // Fall back to original name in case there is no english one.
            $name = $this->parseProperty('original_name');
        }
        $this->name = $name;
    }

    /**
     * Parse and set the status.
     */
    private function parseStatus(): void
    {
        $status = $this->parseProperty('status');
        $this->status = $status;

        if ($status === 'Returning Series') {
            $this->status = 'Continuing';
        }
    }

    /**
     * Parse and set the first aired date.
     */
    private function parseFirstAired(): void
    {
        $first_aired = $this->parseProperty('first_air_date');
        if ($first_aired === null) {
            return;
        }
        try {
            $this->first_aired = Carbon::parse($this->getResponse()->first_air_date);
        } catch (\Exception $e) {
            Log::warning('Could not parse first_air_date for TMDB'.$this->getResponse()->id);
        }
    }

    /**
     * Parse and set the networks.
     */
    private function parseNetworks(): void
    {
        if (empty($this->getResponse()->networks)) {
            return;
        }

        if (\is_array($this->getResponse()->networks)) {
            foreach ($this->getResponse()->networks as $network) {
                $this->networks[] = $network->name;
            }
        }
    }

    /**
     * Parse and set the runtime.
     */
    private function parseRuntime(): void
    {
        if (empty($this->getResponse()->episode_run_time)) {
            return;
        }
        if (\is_array($this->getResponse()->episode_run_time)) {
            $runtime = implode('m, ', $this->getResponse()->episode_run_time).'m';
        } else {
            $runtime = $this->getResponse()->episode_run_time;
        }

        $this->runtime = $runtime;
    }

    /**
     * Parse and set the languages.
     */
    private function parseLanguages(): void
    {
        $languages = [];
        if (isset($this->getResponse()->languages) === true && \is_array($this->getResponse()->languages)) {
            $languages = $this->getResponse()->languages;
        }
        if (isset($this->getResponse()->original_language) === true && $this->getResponse()->original_language !== ''
            && \in_array($this->getResponse()->original_language, $languages, true) === false) {
            $languages[] = $this->getResponse()->original_language;
        }
        $this->languages = $languages;
    }

    /**
     * Parse and set the countries.
     */
    private function parseCountries(): void
    {
        if (isset($this->getResponse()->origin_country) === true && \is_array($this->getResponse()->origin_country)) {
            $this->countries = $this->getResponse()->origin_country;
        }
    }

    /**
     * Parse and set the seasons.
     */
    private function parseSeasons(): void
    {
        if (isset($this->getResponse()->seasons) === true && \is_array($this->getResponse()->seasons)) {
            foreach ($this->getResponse()->seasons as $season) {
                $this->seasons[] = new RSeason($season);
            }
        }
    }

    /**
     * Parse and set the genres.
     */
    private function parseGenres(): void
    {
        $tmdbGenres = config('favon.tmdb_genres');
        if (isset($this->getResponse()->genres) === true && \is_array($this->getResponse()->genres)) {
            foreach ($this->getResponse()->genres as $genre) {
                // If this genre is interesting to us, convert it to the equivalent name in our database
                if ($genre->name && isset($tmdbGenres[$genre->name])) {
                    $this->genres[] = $tmdbGenres[$genre->name];
                }
            }
        }
    }

    /**
     * Get response object as associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'status' => $this->getStatus(),
            'first_aired' => $this->getFirstAired(),
            'runtime' => $this->getRuntime(),
            'plot' => $this->getPlot(),
            'poster' => $this->getPoster(),
            'banner' => $this->getBanner(),
            'homepage' => $this->getHomepage(),
            'tmdb_id' => $this->getTmdbId(),
            'popularity' => $this->getPopularity(),
        ];
    }
}
