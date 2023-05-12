<?php declare(strict_types=1);

namespace App;

use App\Models\Character;
use App\Models\Episode;
use App\Models\CharactersCollection;
use App\Models\Location;
use App\Models\LocationsCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private Client $client;
    private CharactersCollection $charactersCollection;
    private LocationsCollection $locationsCollection;

    public function __construct()
    {
        $this->client = new Client();
        $this->charactersCollection = new CharactersCollection();
        $this->locationsCollection = new LocationsCollection();
    }

    public function getCharactersContents(): ?CharactersCollection
    {
        try {
            $page = $_GET['page'] ?? 1;

            if (!Cache::has('characters-all-' . $page)) {
                $url = 'https://rickandmortyapi.com/api/character';
                $url .= '?page=' . $page;
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();
                Cache::remember('characters-all-' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('characters-all-' . $page);
            }
            $charactersContents = json_decode($responseJson);

            foreach ($charactersContents->results as $character) {
                $firstEpisodeUrl = $character->episode[0];
                $firstEpisodeCacheKey = 'episode-all-' . $character->id;

                if (!Cache::has($firstEpisodeCacheKey)) {
                    $firstEpisodeJson = $this->client->request('GET', $firstEpisodeUrl)->getBody()->getContents();
                    Cache::remember($firstEpisodeCacheKey, $firstEpisodeJson);
                } else {
                    $firstEpisodeJson = Cache::get($firstEpisodeCacheKey);
                }
                $firstEpisode = json_decode($firstEpisodeJson);

                $this->charactersCollection->add(new Character(
                    $character->id,
                    $character->image,
                    $character->name,
                    $character->status,
                    $character->species,
                    $character->location->name,
                    new Episode($firstEpisode->name),
                ));
            }
            return $this->charactersCollection;

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getByName($name): ?CharactersCollection
    {
        try {
            if (!Cache::has('characters-by-name-' . $name)) {
                $name = $_GET['search'];
                $url = "https://rickandmortyapi.com/api/character/?name=$name";
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();

                Cache::remember('characters-by-name-' . $name, $responseJson);
            } else {
                $responseJson = Cache::get('characters-by-name-' . $name);
            }
            $charactersContents = json_decode($responseJson);

            foreach ($charactersContents->results as $character) {
                $firstEpisodeUrl = $character->episode[0];
                $firstEpisodeCacheKey = 'episode-by-name-' . $character->id;

                if (!Cache::has($firstEpisodeCacheKey)) {
                    $firstEpisodeJson = $this->client->request('GET', $firstEpisodeUrl)->getBody()->getContents();
                    Cache::remember($firstEpisodeCacheKey, $firstEpisodeJson);
                } else {
                    $firstEpisodeJson = Cache::get($firstEpisodeCacheKey);
                }
                $firstEpisode = json_decode($firstEpisodeJson);

                $this->charactersCollection->add(new Character(
                    $character->id,
                    $character->image,
                    $character->name,
                    $character->status,
                    $character->species,
                    $character->location->name,
                    new Episode($firstEpisode->name)
                ));
            }
            return $this->charactersCollection;

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getByEpisode($episodeId): ?CharactersCollection
    {
        try {
            if (!Cache::has('episode-' . $episodeId)) {
                $episodeId = $_GET['id'];
                $url = "https://rickandmortyapi.com/api/episode/$episodeId";
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();

                Cache::remember('episode-' . $episodeId, $responseJson);
            } else {
                $responseJson = Cache::get('episode-' . $episodeId);
            }
            $episodeContents = json_decode($responseJson);

            foreach ($episodeContents->characters as $characterUrl) {

                $characterResponse = $this->client->request('GET', $characterUrl);
                $characterResponseJson = $characterResponse->getBody()->getContents();
                $charactersContents = json_decode($characterResponseJson);

                $firstEpisodeUrl = $charactersContents->episode[0];
                $firstEpisodeCacheKey = 'characters-firstEpisode-' . $charactersContents->id;

                if (!Cache::has($firstEpisodeCacheKey)) {
                    $firstEpisodeJson = $this->client->request('GET', $firstEpisodeUrl)->getBody()->getContents();
                    Cache::remember($firstEpisodeCacheKey, $firstEpisodeJson);
                } else {
                    $firstEpisodeJson = Cache::get($firstEpisodeCacheKey);
                }
                $firstEpisode = json_decode($firstEpisodeJson);

                $this->charactersCollection->add(new Character(
                    $charactersContents->id,
                    $charactersContents->image,
                    $charactersContents->name,
                    $charactersContents->status,
                    $charactersContents->species,
                    $charactersContents->location->name,
                    new Episode($firstEpisode->name)
                ));
            }
            return $this->charactersCollection;

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getLocations(): ?LocationsCollection
    {
        $page = 1;
        do {
            try {
                if (!Cache::has('locations-' . $page)) {
                    $url = "https://rickandmortyapi.com/api/location?page=$page";
                    $response = $this->client->request('GET', $url);
                    $responseJson = $response->getBody()->getContents();

                    Cache::remember('locations-' . $page, $responseJson);
                } else {
                    $responseJson = Cache::get('locations-' . $page);
                }
                $locationsContents = json_decode($responseJson);

                foreach ($locationsContents->results as $location) {
                    $this->locationsCollection->add(new Location(
                        $location->id,
                        $location->name
                    ));
                }
            } catch (GuzzleException $exception) {
                return null;
            }
            $page++;

        } while (!empty($locationsContents->info->next));

        return $this->locationsCollection;
    }

    public function getByLocation($locationId): ?CharactersCollection
    {
        try {
            if (!Cache::has('characters-by-location-' . $locationId)) {
                $locationId = $_GET['id'];
                $url = "https://rickandmortyapi.com/api/location/$locationId";
                $response = $this->client->request('GET', $url);
                $responseJson = $response->getBody()->getContents();

                Cache::remember('characters-by-location-' . $locationId, $responseJson);
            } else {
                $responseJson = Cache::get('characters-by-location-' . $locationId);
            }
            $charactersContents = json_decode($responseJson);

            foreach ($charactersContents->residents as $residentUrl) {

                $residentResponse = $this->client->request('GET', $residentUrl);
                $residentResponseJson = $residentResponse->getBody()->getContents();
                $residentContents = json_decode($residentResponseJson);

                $episodeUrl = $residentContents->episode[0];
                $episodeCacheKey = 'episode-by-location-' . $residentContents->id;

                if (!Cache::has($episodeCacheKey)) {
                    $episodeJson = $this->client->request('GET', $episodeUrl)->getBody()->getContents();
                    Cache::remember($episodeCacheKey, $episodeJson);
                } else {
                    $episodeJson = Cache::get($episodeCacheKey);
                }
                $episode = json_decode($episodeJson);

                $this->charactersCollection->add(new Character(
                    $residentContents->id,
                    $residentContents->image,
                    $residentContents->name,
                    $residentContents->status,
                    $residentContents->species,
                    $residentContents->location->name,
                    new Episode($episode->name)
                ));
            }
            return $this->charactersCollection;

        } catch (GuzzleException $exception) {
            return null;
        }
    }
}