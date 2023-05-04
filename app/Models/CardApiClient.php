<?php declare(strict_types=1);

namespace App\Models;

use GuzzleHttp\Client;

class CardApiClient
{
    private Client $client;
    private CardsCollection $cardsCollection;

    public function __construct()
    {
        $this->client = new Client();
        $this->cardsCollection = new CardsCollection();
    }

    public function getCardsContents(): CardsCollection
    {
        $characterIds = [];
        for ($i = 0; $i < 18; $i++) {
            $characterIds[] = rand(1, 826);
        }
        $queryString = implode(',', $characterIds);

        $url = "https://rickandmortyapi.com/api/character/$queryString";
        $response = $this->client->request('GET', $url);
        $cardContents = json_decode($response->getBody()->getContents());

        foreach ($cardContents as $card) {
            $this->cardsCollection->add(new Card(
                $card->image,
                $card->name,
                $card->status,
                $card->species,
                $card->location->name,
                $card->episode[0]
            ));
        }
        return $this->cardsCollection;
    }
}