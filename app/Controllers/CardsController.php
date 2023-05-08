<?php declare(strict_types=1);

namespace App\Controllers;

use App\CardApiClient;
use App\View;

class CardsController
{
    public function getIndex(): View
    {
        $apiClient = new CardApiClient();
        $cardsCollection = $apiClient->getCardsContents();
        return new View('index', ['cards' => $cardsCollection->getCollection()]);
    }

    public function getCards(): View
    {
        $apiClient = new CardApiClient();
        $cardsCollection = $apiClient->getCardsContents();
        return new View('cards', ['cards' => $cardsCollection->getCollection()]);
    }
}