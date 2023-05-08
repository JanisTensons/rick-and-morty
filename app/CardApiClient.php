<?php declare(strict_types=1);

namespace App;

use App\Models\Card;
use App\Models\CardsCollection;
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
        while (count($characterIds) < 36) {
            $characterId = mt_rand(1, 826);
            if (!in_array($characterId, $characterIds)) {
                $characterIds[] = $characterId;
            }
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