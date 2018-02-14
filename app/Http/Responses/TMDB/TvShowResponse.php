<?php

namespace App\Http\Responses\TMDB;

use App\Http\Responses\BaseResponse;
use App\Http\Responses\TMDB\Models\RSeason;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
    protected $network;

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
    protected $languages = array();

    /**
     * @var RSeason[]
     */
    protected $seasons = array();

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
    public function getNetwork(): ?string
    {
        return $this->network;
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
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @return RSeason[]
     */
    public function getSeasons(): array
    {
        return $this->seasons;
    }

    public function parseResponse()
    {
        $this->name = $this->parseProperty('name');
        $this->parseStatus();
        $this->parseFirstAired();
        $this->parseNetwork();
        $this->parseRuntime();
        $this->plot = $this->parseProperty('overview');
        $this->poster = $this->parseProperty('poster_path');
        $this->banner = $this->parseProperty('backdrop_path');
        $this->homepage = $this->parseProperty('homepage');
        $this->tmdb_id = $this->parseProperty('id', BaseResponse::TYPE_INT);
        $this->popularity = $this->parseProperty('popularity', BaseResponse::TYPE_FLOAT);
        $this->parseLanguages();
        $this->parseSeasons();
    }

    /**
     * Parse and set the status.
     */
    private function parseStatus(): void
    {
        $status = $this->parseProperty('status');
        if ($status === 'Returning Series') {
            $this->status = 'Continuing';
        } else if (\in_array($status, ['Planned', 'In Production', 'Ended', 'Canceled', 'Pilot'], true)) {
            $this->status = $status;
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
     * Parse and set the network.
     */
    private function parseNetwork(): void
    {
        if (empty($this->getResponse()->networks)) {
            return;
        }

        if (\is_array($this->getResponse()->networks)) {
            $network = array_reduce($this->getResponse()->networks, function ($acc, $item) {
                $acc .= $item->name.', ';
                return $acc;
            });
            $network = rtrim($network, ', ');
        } else {
            $network = (string) $this->getResponse()->networks;
        }

        $this->network = $network;
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
        if (isset($this->getResponse()->languages) === true && \is_array($this->getResponse()->languages)) {
            $this->languages = $this->getResponse()->languages;
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
            'network' => $this->getNetwork(),
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