<?php

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\CardsData;
use Bga\Games\AgileAndCo\IDeckAdapter;

class FakeDeck implements IDeckAdapter
{
    private $cards = [];
    private $locations = [];

    public function __construct()
    {
        $data = array_map(function ($data) {
            $data['id'] = 0;
            $data['player_id'] = 0;
            $data['location'] = '';
            $data['index'] = 0;
            return $data;
        }, CardsData::$instances);
        $this->createCards($data, 'deck');
    }

    /**
     * Create cards with the given data and place them in the specified location.
     *
     * @param array $cardData
     * @param string $location
     */
    public function createCards(array $cardData, string $location): void
    {
        foreach ($cardData as $data) {
            $numberOfCards = $data['nbr'] ?? 1;
            unset($data['nbr']);
            for ($i = 0; $i < $numberOfCards; $i++) {
                $cardId = $this->generateCardId();
                $this->cards[$cardId] = $data;
                $this->locations[$location][] = $cardId;
            }
        }
    }


    /**
     * Move all cards of a specific type into the given location with an auto-incremented index.
     *
     * @param string $type
     * @param string $location
     */
    public function deckify(string $type, string $location): void
    {
        $cardsOfType = $this->getCardsOfType($type);
        foreach ($cardsOfType as $cardId) {
            $this->moveCard($cardId, $location);
        }
    }

    /**
     * Move a card to a specified location with an optional index or player ID.
     *
     * @param int $cardId
     * @param string $location
     * @param int|null $index
     * @param int|null $playerId
     */
    public function moveCard(int $cardId, string $location, ?int $index = null, ?int $playerId = null): void
    {
        // Remove card from current location
        foreach ($this->locations as $loc => $cards) {
            $key = array_search($cardId, $cards);
            if ($key !== false) {
                unset($this->locations[$loc][$key]);
                $this->locations[$loc] = array_values($this->locations[$loc]); // Reindex array
                break;
            }
        }

        // Determine the target location key
        $targetLocationKey = $playerId !== null ? "{$location}_{$playerId}" : $location;

        // Add card to new location
        if ($index !== null) {
            if (!isset($this->locations[$targetLocationKey])) {
                $this->locations[$targetLocationKey] = [];
            }
            array_splice($this->locations[$targetLocationKey], $index, 0, [$cardId]);
        } else {
            $this->locations[$targetLocationKey][] = $cardId;
        }
    }

    /**
     * Retrieve cards of a specific type.
     *
     * @param string $type
     * @param int|null $typeArg
     * @return array
     */
    public function getCardsOfType(string $type, ?int $typeArg = null): array
    {
        $cardsOfType = [];
        foreach ($this->cards as $cardId => $data) {
            if (isset($data['type']) && $data['type'] === $type) {
                if ($typeArg === null || (isset($data['type_arg']) && $data['type_arg'] === $typeArg)) {
                    $location = $this->findCardLocation($cardId);
                    $index = array_search($cardId, $this->locations[$location]);
                    [$loc, $playerId] = $this->explodeLocation($location);

                    $cardsOfType[] = [
                        'id' => $cardId,
                        'type' => $data['type'],
                        'type_arg' => $data['type_arg'],
                        'location' => $loc,
                        'player_id' => $playerId,
                        'index' => $index,
                    ];
                }
            }
        }
        return $cardsOfType;
    }


    /**
     * Retrieve cards in a specific location.
     *
     * @param string $location
     * @param int|null $index
     * @param int|null $playerId
     * @return array
     */
    public function getCardsInLocation(string $location, ?int $index = null, ?int $playerId = null): array
    {
        $targetLocationKey = $playerId !== null ? "{$location}_{$playerId}" : $location;

        if (!isset($this->locations[$targetLocationKey])) {
            return [];
        }

        $cardsInLocation = $this->locations[$targetLocationKey];
        $result = [];

        foreach ($cardsInLocation as $idx => $cardId) {
            if ($index !== null && $idx !== $index) {
                continue;
            }
            $data = $this->cards[$cardId];

            $result[] = [
                'id' => $cardId,
                'type' => $data['type'],
                'type_arg' => $data['type_arg'],
                'location' => $location,
                'player_id' => $playerId ?? 0,
                'index' => $idx,
            ];
        }

        return $result;
    }

    /**
     * Shuffle cards in a specific location.
     *
     * @param string $location
     */
    public function shuffle(string $location): void
    {
        if (isset($this->locations[$location])) {
            shuffle($this->locations[$location]);
        }
    }

    /**
     * Pick a number of cards from a location to a player's location.
     *
     * @param int $number
     * @param string $fromLocation
     * @param string $toLocation
     * @param int $playerId
     */
    public function pickCardsForLocation(int $number, string $fromLocation, string $toLocation, int $playerId): void
    {
        $fromLocationKey = "{$fromLocation}_{$playerId}";
        $toLocationKey = "{$toLocation}_{$playerId}";

        if (isset($this->locations[$fromLocationKey])) {
            $pickedCards = array_splice($this->locations[$fromLocationKey], 0, $number);
            foreach ($pickedCards as $cardId) {
                $this->moveCard($cardId, $toLocationKey);
            }
        }
    }

    /**
     * Play a card (move to discard or similar).
     *
     * @param int $cardId
     */
    public function playCard(int $cardId): void
    {
        $this->moveCard($cardId, 'discard');
    }

    /**
     * Move all cards from one location to another.
     *
     * @param string $fromLocation
     * @param string $toLocation
     * @param int|null $fromIndex
     * @param int|null $toIndex
     * @param int|null $playerId
     */
    public function moveAllCardsInLocation(string $fromLocation, string $toLocation, ?int $fromIndex = null, ?int $toIndex = null, ?int $playerId = null): void
    {
        $fromLocationKey = $playerId !== null ? "{$fromLocation}_{$playerId}" : $fromLocation;
        $toLocationKey = $playerId !== null ? "{$toLocation}_{$playerId}" : $toLocation;

        if (isset($this->locations[$fromLocationKey])) {
            $cardsToMove = $this->locations[$fromLocationKey];
            if ($fromIndex !== null) {
                $cardsToMove = array_slice($cardsToMove, $fromIndex);
            }
            foreach ($cardsToMove as $cardId) {
                $this->moveCard($cardId, $toLocationKey, $toIndex);
            }
            $this->locations[$fromLocationKey] = [];
        }
    }

    private function generateCardId(): int
    {
        return count($this->cards) + 1;
    }

    /**
     * Find the location of a specific card.
     *
     * @param int $cardId
     * @return string|null
     */
    private function findCardLocation(int $cardId): ?string
    {
        foreach ($this->locations as $location => $cards) {
            if (in_array($cardId, $cards)) {
                return $location;
            }
        }
        return null;
    }

    private function explodeLocation(string $location): array
    {
        $parts = explode('_', $location);
        $playerId = isset($parts[1]) ? (int)$parts[1] : 0;
        return [$parts[0], $playerId];
    }

}
