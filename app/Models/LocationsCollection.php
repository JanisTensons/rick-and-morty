<?php declare(strict_types=1);

namespace App\Models;

class LocationsCollection
{
    private array $collection = [];

    public function add(Location $location): void
    {
        $this->collection[] = $location;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }
}