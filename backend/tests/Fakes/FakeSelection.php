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

use Bga\Games\AgileAndCo\Rules;

class FakeSelection
{
    private int $playerId;
    private array $cards;
    public readonly Rules $rules;

    public function __construct(Rules $rules, int $playerId, array $cards)
    {
        $this->playerId = $playerId;
        $this->cards = $cards;
        $this->rules = $rules;
    }

    public function incomingJson() : array
    {
        $state = $this->rules->getGameState();
        return array_map(function ($selected) use($state) {
            return [
                'zone' => $selected[0],
                'index' => $selected[1],
                'name' => $state['_private'][$this->playerId][$selected[0]][$selected[1]]
            ];
        }, $this->cards);
    }

    public function count() : int
    {
        return count($this->cards);
    }

    public function getCardIds() : array
    {
        return array_map(function ($card) {
            return $card->id;
        }, $this->getCards());
    }

    public function getCards() : array
    {
        return array_map(function ($selected) {
            $zone = match ($selected[0]) {
                'teams' => $this->rules->repo->loadAndSortByIndexFromLocation('teams', $this->playerId),
                'products' => $this->rules->repo->loadAndSortByIndexFromLocation('products', $this->playerId),
                'company' => $this->rules->repo->loadAndSortByUsageFromLocation('company', $this->playerId),
                'potential' => $this->rules->repo->loadAndSortByUsageFromLocation('potential', $this->playerId),
                'conference' => $this->rules->repo->loadAndSortByUsageFromLocation('conference', $this->playerId),
                default => throw new \BgaUserException('Invalid card zone'),
            };
            return $zone[$selected[1]];
        }, $this->cards);
    }
}