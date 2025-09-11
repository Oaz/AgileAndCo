<?php
/*
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Bga\Games\AgileAndCo\Tests;

use Bga\Games\AgileAndCo\IDeckAdapter;
use Random\Randomizer;
use Random\Engine\Mt19937;

class FakeDeck implements IDeckAdapter
{
    private $cards = [];
    private $locations = [];
    private Randomizer $randomizer;

    public function __construct() {
        $seed = 123456789;
        $engine = new Mt19937($seed);
        $this->randomizer = new Randomizer($engine);
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
                $data['id'] = $cardId;
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
        foreach ($cardsOfType as $index => $cardData) {
            $this->moveCard($cardData['id'], $location);
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
        $this->doMoveCard(false, $cardId, $location, $index, $playerId);
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
        $result = [];
        foreach ($this->locations as $locationKey => $cards) {
            if(!$this->isLocationKey($locationKey, $location, $playerId))
                continue;
            [$loc, $plId] = $this->explodeLocation($locationKey);
            foreach ($cards as $idx => $cardId) {
                if ($index !== null && $idx !== $index) {
                    continue;
                }
                $data = $this->cards[$cardId];

                $result[] = [
                    'id' => $cardId,
                    'type' => $data['type'],
                    'type_arg' => $data['type_arg'],
                    'location' => $loc,
                    'player_id' => $plId,
                    'index' => $idx,
                ];
            }
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
            $this->randomizer->shuffleArray($this->locations[$location]);
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
        $fromLocationKey = $this->getLocationKey($fromLocation, null);
        $toLocationKey = $this->getLocationKey($toLocation, $playerId);

        if (isset($this->locations[$fromLocationKey])) {
            $pickedCards = array_splice($this->locations[$fromLocationKey], 0, $number);
            foreach ($pickedCards as $cardId) {
                $this->moveCard($cardId, $toLocationKey);
            }
            $this->reindexLocation($fromLocationKey);
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
        $fromLocationKey = $this->getLocationKey($fromLocation, $playerId);
        $toLocationKey = $this->getLocationKey($toLocation, $playerId);

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

    public function getLocationKey(string $location, ?int $playerId): string
    {
        return $playerId !== null ? "{$location}_{$playerId}" : $location;
    }

    public function isLocationKey(string $locationKey, string $location, ?int $playerId): bool
    {
        return $playerId !== null
            ? $locationKey == $this->getLocationKey($location, $playerId)
            : str_starts_with($locationKey, $location);
    }

    private function unloadCardFromLocations(int $cardId, bool $reindex): void
    {
        foreach ($this->locations as $loc => $cards) {
            $key = array_search($cardId, $cards);
            if ($key !== false) {
                unset($this->locations[$loc][$key]);
                if($reindex)
                    $this->reindexLocation($loc);
                break;
            }
        }
    }

    private function reindexLocation(string $locationKey): void
    {
        $this->locations[$locationKey] = array_values($this->locations[$locationKey]);
    }

    public function doMoveCard(bool $reindex, int $cardId, string $location, ?int $index = null, ?int $playerId = null): void
    {
        $this->unloadCardFromLocations($cardId, reindex: $reindex);
        $targetLocationKey = $this->getLocationKey($location, $playerId);
        if ($index !== null) {
            if (!isset($this->locations[$targetLocationKey])) {
                $this->locations[$targetLocationKey] = [];
            }
            $this->locations[$targetLocationKey][$index] = $cardId;
        } else {
            $this->locations[$targetLocationKey][] = $cardId;
        }
    }

}
