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

use Deck;

class DeckAdapter implements IDeckAdapter
{
    private Deck $deck;

    public function __construct(Deck $deck)
    {
        $this->deck = $deck;
        $this->deck->init("card");
        $this->deck->autoreshuffle = true;
    }

    public function createCards(array $cardData, string $location): void
    {
        $this->deck->createCards($cardData, $location);
    }

    public function deckify($type, $location): void
    {
        $i = 0;
        foreach ($this->deck->getCardsOfType($type) as $card) {
            $this->deck->moveCard($card['id'], $location, $i++);
        }
    }

    public function moveCard(int $cardId, string $location, ?int $index = null, ?int $playerId = null): void
    {
        $locationArg = $this->calculateLocationArg($index, $playerId);
        $this->deck->moveCard($cardId, $location, $locationArg);
    }

    public function getCardsOfType(string $type, ?int $typeArg = null): array
    {
        return $this->deck->getCardsOfType($type, $typeArg);
    }

    public function getCardsInLocation(string $location, ?int $index = null, ?int $playerId = null): array
    {
        if ($playerId !== null && $index === null) {
            $allCards = $this->assignIndexAndPlayerIdFields($this->deck->getCardsInLocation($location));
            $playerCards = array_filter($allCards, function ($card) use ($playerId) {
                return $card['player_id'] === $playerId;
            });
            return $playerCards;
        }
        $locationArg = $this->calculateLocationArg($index, $playerId);
        return $this->assignIndexAndPlayerIdFields($this->deck->getCardsInLocation($location, $locationArg));
    }

    private function assignIndexAndPlayerIdFields($cards) {
        return array_map(function ($card) {
            $card['player_id'] = intdiv($card['location_arg'], 100);
            $card['index'] = $card['location_arg'] % 100;
            return $card;
        }, $cards);
    }

    public function shuffle(string $location): void
    {
        $this->deck->shuffle($location);
    }

    public function pickCardsForLocation(int $number, string $fromLocation, string $toLocation, int $playerId): void
    {
        $toLocationArg = $this->calculateLocationArg(null, $playerId);
        $this->deck->pickCardsForLocation($number, $fromLocation, $toLocation, $toLocationArg);
    }

    public function playCard(int $cardId): void
    {
        $this->deck->playCard($cardId);
    }

    public function moveAllCardsInLocation(string $fromLocation, string $toLocation, ?int $fromIndex = null, ?int $toIndex = null, ?int $playerId = null): void
    {
        $fromLocationArg = $this->calculateLocationArg($fromIndex, $playerId);
        $toLocationArg = $this->calculateLocationArg($toIndex, $playerId);
        $this->deck->moveAllCardsInLocation($fromLocation, $toLocation, $fromLocationArg, $toLocationArg);
    }

    private function calculateLocationArg(?int $index, ?int $playerId): ?int
    {
        if ($index !== null && $playerId !== null) {
            return $playerId * 100 + $index;
        } elseif ($index !== null) {
            return $index;
        } elseif ($playerId !== null) {
            return $playerId * 100;
        }
        return null;
    }
}
