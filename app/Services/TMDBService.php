<?php

namespace App\Services;

use Carbon\Carbon;
use App\Http\Clients\TMDBClient;
use Psr\Log\LoggerInterface;
use Intervention\Image\Facades\Image;
use App\Exceptions\GenericAPIException;
use App\Exceptions\NoAPIResultsFoundException;

class TMDBService
{
    /**
     * @var TMDBClient
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TMDBService constructor.
     * @param TMDBClient $client
     * @param LoggerInterface $logger
     */
    public function __construct(TMDBClient $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Get all application relevant data from a TMDB tv season entry by id and number.
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
            $this->logger->error($e->getCode().': '.$e->getMessage());

            return null;
        }

        $videos = [];
        foreach ($responseVideos->getResponse() as $video) {
            $videos[] = [
                'name' => $video->name,
                'key' => $video->key,
                'type' => $video->type,
            ];
        }

        $credits = [];
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
        $episodes = [];
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
            'number' => (int) $response->getResponse()->season_number,
            'name' => $response->getResponse()->name,
            'summary' => $response->getResponse()->overview,
            'poster' => $response->getResponse()->poster_path,
            'tmdb_id' => (int) $response->getResponse()->id,
            'videos' => $videos,
            'episodes' => $episodes,
            'credits' => $credits,
        ];
    }

    /**
     * Fetch images from TMDB and store locally.
     *
     * @param string $type
     * @param string $path
     */
    public function fetchImages(string $type, string $path) : void
    {
        $sizes = config('media.'.$type.'_sizes');
        $base_path = config('media.image_base_path');
        foreach ($sizes as $size) {
            $this->fetchImage($base_path.'/'.$size.$path, public_path('images/'.$type.'/'.$size.'/'.basename($path)), 0);
        }
    }

    /**
     * Fetch a single image, with a maximum of 10 tries.
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
