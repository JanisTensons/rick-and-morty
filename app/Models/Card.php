<?php declare(strict_types=1);

namespace App\Models;

use GuzzleHttp\Client;

class Card
{
    private Client $client;
    private string $url;
    private string $name;
    private string $status;
    private string $species;
    private string $location;
    private string $episode;

    public function __construct(
        string $url,
        string $name,
        string $status,
        string $species,
        string $location,
        string $episode
    )

    {
        $this->client = new Client();
        $this->url = $url;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->location = $location;
        $this->episode = $episode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getEpisode(): string
    {
        $url = $this->episode;
        $response = $this->client->request('GET', $url);
        $episodeContents = json_decode($response->getBody()->getContents());
        return $episodeContents->name;
    }
}