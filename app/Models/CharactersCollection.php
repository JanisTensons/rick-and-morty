<?php declare(strict_types=1);

namespace App\Models;

class CharactersCollection
{
    private array $collection = [];

    public function add(Character $character): void
    {
        $this->collection[] = $character;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}