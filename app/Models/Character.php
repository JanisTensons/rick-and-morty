<?php declare(strict_types=1);

namespace App\Models;

class Character
{
    private int $id;
    private string $url;
    private string $name;
    private string $status;
    private string $species;
    private string $location;
    private Episode $episode;

    public function __construct(
        int $id,
        string $url,
        string $name,
        string $status,
        string $species,
        string $location,
        Episode $episode
    )

    {
        $this->id = $id;
        $this->url = $url;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->location = $location;
        $this->episode = $episode;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getEpisode(): Episode
    {
        return $this->episode;
    }
}