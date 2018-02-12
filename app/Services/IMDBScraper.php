<?php

namespace App\Services;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class IMDBScraper
{
    /**
     * HTTP client.
     * @var Client
     */
    protected $client;

    /**
     * Array with all resulting ids.
     * @var array
     */
    protected $results;

    /**
     * IMDBScraper constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->results = [];
    }

    /**
     * Recursive function to scrape a list of IMDB entries until no `next` page is found
     * Get IMDB id for each entry.
     *
     * @param string $url
     * @return array
     */
    public function scrape(string $url) : array
    {
        $crawler = $this->client->request('GET', $url);
        $crawler->filter('.lister-item-header a')->each(function (Crawler $item) {
            preg_match('/tt\d{7}/', $item->attr('href'), $matches);
            $this->results[] = $matches[0];
        });
        $link = $crawler->filter('.lister-page-next.next-page')->first();
        try {
            return $this->scrape($link->link()->getUri());
        } catch (\InvalidArgumentException $e) { // No next-link found
            return $this->results;
        }
    }
}
