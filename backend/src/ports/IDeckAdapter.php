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

namespace Bga\Games\AgileAndCo;

interface IDeckAdapter
{
    /**
     * Create cards with the given data and place them in the specified location.
     *
     * @param array $cardData
     * @param string $location
     */
    public function createCards(array $cardData, string $location): void;

    /**
     * Move all cards of a specific type into the given location with an auto-incremented index.
     *
     * @param string $type
     * @param string $location
     */
    public function deckify(string $type, string $location): void;

    /**
     * Move a card to a specified location with an optional index or player ID.
     *
     * @param int $cardId
     * @param string $location
     * @param int|null $index
     * @param int|null $playerId
     */
    public function moveCard(int $cardId, string $location, ?int $index = null, ?int $playerId = null): void;

    /**
     * Retrieve cards of a specific type.
     *
     * @param string $type
     * @param int|null $typeArg
     * @return array
     */
    public function getCardsOfType(string $type, ?int $typeArg = null): array;

    /**
     * Retrieve cards in a specific location.
     *
     * @param string $location
     * @param int|null $index
     * @param int|null $playerId
     * @return array
     */
    public function getCardsInLocation(string $location, ?int $index = null, ?int $playerId = null): array;

    /**
     * Shuffle cards in a specific location.
     *
     * @param string $location
     */
    public function shuffle(string $location): void;

    /**
     * Pick a number of cards from a location to a player's location.
     *
     * @param int $number
     * @param string $fromLocation
     * @param string $toLocation
     * @param int $playerId
     */
    public function pickCardsForLocation(int $number, string $fromLocation, string $toLocation, int $playerId): void;

    /**
     * Play a card (move to discard or similar).
     *
     * @param int $cardId
     */
    public function playCard(int $cardId): void;

    /**
     * Move all cards from one location to another.
     *
     * @param string $fromLocation
     * @param string $toLocation
     * @param int|null $fromIndex
     * @param int|null $toIndex
     * @param int|null $playerId
     */
    public function moveAllCardsInLocation(string $fromLocation, string $toLocation, ?int $fromIndex = null, ?int $toIndex = null, ?int $playerId = null): void;
}
