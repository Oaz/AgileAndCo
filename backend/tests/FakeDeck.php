<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\IDeckAdapter;

class FakeDeck implements IDeckAdapter
{

    public function createCards(array $cardData, string $location): void
    {
        // TODO: Implement createCards() method.
    }

    public function deckify(string $type, string $location): void
    {
        // TODO: Implement deckify() method.
    }

    public function moveCard(int $cardId, string $location, ?int $index = null, ?int $playerId = null): void
    {
        // TODO: Implement moveCard() method.
    }

    public function getCardsOfType(string $type, ?int $typeArg = null): array
    {
        // TODO: Implement getCardsOfType() method.
    }

    public function getCardsInLocation(string $location, ?int $index = null, ?int $playerId = null): array
    {
        // TODO: Implement getCardsInLocation() method.
    }

    public function shuffle(string $location): void
    {
        // TODO: Implement shuffle() method.
    }

    public function pickCardsForLocation(int $number, string $fromLocation, string $toLocation, int $playerId): void
    {
        // TODO: Implement pickCardsForLocation() method.
    }

    public function playCard(int $cardId): void
    {
        // TODO: Implement playCard() method.
    }

    public function moveAllCardsInLocation(string $fromLocation, string $toLocation, ?int $fromIndex = null, ?int $toIndex = null, ?int $playerId = null): void
    {
        // TODO: Implement moveAllCardsInLocation() method.
    }
}