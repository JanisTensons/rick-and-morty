<?php declare(strict_types=1);

namespace App;

class View
{
    private string $template;
    private array $charactersCollection;

    public function __construct(string $template, array $charactersCollection)
    {
        $this->template = $template;
        $this->charactersCollection = $charactersCollection;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getCharactersCollection(): array
    {
        return $this->charactersCollection;
    }
}