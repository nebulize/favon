<?php

namespace App\Services;

use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;
use App\Http\Clients\TMDBClient;
use Carbon\Carbon;
use Illuminate\Contracts\Logging\Log;
use Intervention\Image\Facades\Image;

class TMDBService
{
    /**
     * @var TMDBClient
     */
    protected $client;

    /**
     * @var Log
     */
    protected $logger;

    /**
     * TMDBService constructor.
     * @param TMDBClient $client
     * @param Log $logger
     */
    public function __construct(TMDBClient $client, Log $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Get all languages from TMDB in the format used by the application
     *
     * @return array|null
     */
    public function getLanguages() : ?array
    {
        try {
            $response = $this->client->getLanguages();
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }
        $languages = array();
        foreach ($response->getResponse() as $language) {
            $languages[] = array(
                'code' => $language->iso_639_1,
                'name' => $language->english_name
            );
        }
        return $languages;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getPerson(int $id) : ?array
    {
        try {
            $response = $this->client->getPerson($id);
        } catch (NoAPIResultsFoundException $e) {
            $this->logger->warning($e->getMessage());
            return null;
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }

        if (empty($response->getResponse()->gender)) {
            $gender = null;
        } else {
            if ($response->getResponse()->gender === 2) {
                $gender = 'Male';
            } elseif ($response->getResponse()->gender === 1) {
                $gender = 'Female';
            } else {
                $gender = null;
            }
        }

        if (empty($response->getResponse()->birthday) || !property_exists($response->getResponse(), 'birthday')) {
            $birthday = null;
        } else {
            try {
                $birthday = Carbon::parse($response->getResponse()->birthday);
            } catch (\Exception $e) {
                \Log::warning('Could not parse birthday for TMDB'.$id);
                $birthday = null;
            }
        }

        if (empty($response->getResponse()->deathday) || !property_exists($response->getResponse(), 'deathday')) {
            $deathday = null;
        } else {
            try {
                $deathday = Carbon::parse($response->getResponse()->deathday);
            } catch (\Exception $e) {
                \Log::warning('Could not parse deathday for TMDB'.$id);
                $deathday = null;
            }
        }

        return [
            'birthday' => $birthday,
            'deathday' => $deathday,
            'name' => property_exists($response->getResponse(), 'name') ? $response->getResponse()->name : '',
            'gender' => $gender,
            'biography' => property_exists($response->getResponse(), 'biography') ? $response->getResponse()->biography : null,
            'place_of_birth' => property_exists($response->getResponse(), 'place_of_birth') ? $response->getResponse()->place_of_birth : null,
            'photo' => property_exists($response->getResponse(), 'profile_path') ? $response->getResponse()->profile_path : null,
            'tmdb_id' => $response->getResponse()->id,
        ];
    }

    /**
     * Get all application relevant data from a TMDB tv show entry by id
     *
     * @param int $id
     * @return array|null
     */
    public function getTvShow(int $id) : ?array
    {
        try {
            $response = $this->client->getTvShow($id);
            $responseIds = $this->client->getTvShowIds($id);
        } catch (NoAPIResultsFoundException $e) {
            $this->logger->warning($e->getMessage());
            return null;
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }

        if (empty($response->getResponse()->networks)) {
            $network = null;
        } else {
            $network = array_reduce($response->getResponse()->networks, function ($acc, $item) {
                $acc .= $item->name . ', ';
                return $acc;
            });
            $network = rtrim($network, ', ');
        }

        return [
            'name' => $response->getResponse()->name,
            'status' => $response->getResponse()->status === 'Returning Series' ? 'Continuing' : $response->getResponse()->status,
            'first_aired' => empty($response->getResponse()->first_air_date) ? null : Carbon::parse($response->getResponse()->first_air_date),
            'network' => $network,
            'runtime' => empty($response->getResponse()->episode_run_time) ? null : implode('m, ', $response->getResponse()->episode_run_time) . 'm',
            'plot' => $response->getResponse()->overview,
            'poster' => $response->getResponse()->poster_path,
            'banner' => $response->getResponse()->backdrop_path,
            'homepage' => $response->getResponse()->homepage,
            'tmdb_id' => empty($response->getResponse()->id) ? null : (int)$response->getResponse()->id,
            'tvdb_id' => empty($responseIds->getResponse()->tvdb_id) ? null : (int)$responseIds->getResponse()->tvdb_id,
            'imdb_id' => $responseIds->getResponse()->imdb_id,
            'languages' => $response->getResponse()->languages,
            'seasons' => $response->getResponse()->seasons
        ];
    }

    /**
     * Get all application relevant data from a TMDB tv season entry by id and number
     *
     * @param int $id
     * @param int $number
     * @return array|null
     */
    public function getTvSeason(int $id, int $number) : ?array
    {
        try {
            $response = $this->client->getTvSeason($id, $number);
            $responseVideos = $this->client->getTvSeasonVideos($id, $number);
            $responseCredits = $this->client->getTvSeasonCredits($id, $number);
        } catch (NoAPIResultsFoundException $e) {
            $this->logger->warning($e->getMessage());
            return null;
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }

        $videos = array();
        foreach ($responseVideos->getResponse() as $video) {
            $videos[] = [
                'name' => $video->name,
                'key' => $video->key,
                'type' => $video->type,
            ];
        }

        $credits = array();
        foreach ($responseCredits->getResponse()->crew as $person) {
            if (isset($credits[$person->id])) {
                continue;
            }
            $credits[$person->id] = [
                'role' => 'crew',
                'job' => $person->job,
                'person_id' => $person->id,
            ];
        }
        foreach ($responseCredits->getResponse()->cast as $person) {
            if (isset($credits[$person->id])) {
                continue;
            }
            $credits[$person->id] = [
                'role' => 'cast',
                'character' => $person->character,
                'person_id' => $person->id,
            ];
        }
        $episodesResponse = $response->getResponse()->episodes;
        $episodes = array();
        foreach ($episodesResponse as $episode) {
            $episodes[] = [
                'number' => $episode->episode_number,
                'name' => $episode->name,
                'first_aired' =>empty($episode->air_date) ? null : Carbon::parse($episode->air_date),
                'plot' => $episode->overview,
                'tmdb_id' => $episode->id,
            ];
            foreach ($episode->crew as $person) {
                if (isset($credits[$person->id])) {
                    continue;
                }
                $credits[$person->id] = [
                    'role' => 'crew',
                    'job' => $person->job,
                    'person_id' => $person->id,
                ];
            }
        }

        return [
            'first_aired' => empty($response->getResponse()->air_date) ? null : Carbon::parse($response->getResponse()->air_date),
            'number' => (int)$response->getResponse()->season_number,
            'name' => $response->getResponse()->name,
            'summary' => $response->getResponse()->overview,
            'poster' => $response->getResponse()->poster_path,
            'tmdb_id' => (int)$response->getResponse()->id,
            'videos' => $videos,
            'episodes' => $episodes,
            'credits' => $credits
        ];
    }

    /**
     * Fetch images from TMDB and store locally
     *
     * @param string $type
     * @param string $path
     */
    public function fetchImages(string $type, string $path) : void
    {
        $sizes = config('media.'.$type.'_sizes');
        $base_path = config('media.image_base_path');
        foreach ($sizes as $size) {
            $this->fetchImage($base_path . '/' . $size . $path, public_path('images/'.$type.'/'.$size.'/'.basename($path)), 0);
        }
    }

    /**
     * Fetch all recently changed persons
     *
     * @param string|null $start
     * @param string|null $end
     * @param int $page
     * @return \stdClass|null
     */
    public function getChangedPersons(string $start = null, string $end = null, int $page = 1) : ?\stdClass
    {
        try {
            $response = $this->client->getChangedPersons($start, $end, $page);
        } catch (GenericAPIException $e) {
            $this->logger->error($e->getCode() . ': ' . $e->getMessage());
            return null;
        }
        return $response->getResponse();
    }

    /**
     * Fetch a single image, with a maximum of 10 tries
     *
     * @param $from
     * @param $to
     * @param $tries
     * @return bool
     */
    public function fetchImage($from, $to, $tries) : bool
    {
        if ($tries > 10) {
            return false;
        }
        try {
            Image::make($from)->save($to);
        } catch (\Exception $e) {
            return $this->fetchImage($from, $to, $tries + 1);
        }
        return true;
    }

}