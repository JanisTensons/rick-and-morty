<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\View;

class CharacterController
{
    public function getIndex(): View
    {
        $apiClient = new ApiClient();
        $charactersCollection = $apiClient->getCharactersContents();
        return new View('index', ['characters' => null]);
    }

    public function getCharacters(): View
    {
        $apiClient = new ApiClient();
        $charactersCollection = $apiClient->getCharactersContents();
        return new View('characters', ['characters' => $charactersCollection->getCollection()]);
    }

    public function getByName(): ?View
    {
        $apiClient = new ApiClient();
        $charactersCollection = $apiClient->getByName("{$_GET["search"]}");
        if (!empty($charactersCollection)) {
            return new View('characters-by-name', ['characters' => $charactersCollection->getCollection()]);
        }
        return new View('no-characters', ['characters' => null]);
    }

    public function getByEpisode(): View
    {
        $apiClient = new ApiClient();
        $charactersCollection = $apiClient->getByEpisode("{$_GET["id"]}");
        return new View('characters-by-episode', ['characters' => $charactersCollection->getCollection()]);
    }

    public function getLocations(): View
    {
        $apiClient = new ApiClient();
        $locationsCollection = $apiClient->getLocations();
        return new View('locations', ['locations' => $locationsCollection->getCollection()]);
    }

    public function getByLocation(): View
    {
        $apiClient = new ApiClient();
        $charactersCollection = $apiClient->getByLocation("{$_GET["id"]}");
        return new View('characters-by-location', ['characters' => $charactersCollection->getCollection()]);
    }
}