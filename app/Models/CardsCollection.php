<?php declare(strict_types=1);

namespace App\Models;

class CardsCollection
{
    private array $collection = [];

    public function add(Card $card): void
    {
        $this->collection[] = $card;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}