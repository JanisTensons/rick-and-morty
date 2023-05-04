<?php declare(strict_types=1);

namespace App;

class View
{
    private string $template;
    private array $cardsCollection;

    public function __construct(string $template, array $cardsCollection)
    {
        $this->template = $template;
        $this->cardsCollection = $cardsCollection;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getCardsCollection(): array
    {
        return $this->cardsCollection;
    }
}