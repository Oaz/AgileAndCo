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

enum SortForSelection {
    case BY_INDEX;
    case BY_USAGE;
}

class CardSelection
{
    private array $taken;
    private string $usage;
    private array $locations;
    private CardsRepository $repo;

    public function __construct(string $usage, int $playerId, array $allowedLocations, CardsRepository $repo)
    {
        $this->usage = $usage;
        $this->taken = [];
        $this->locations =  array_reduce(
            array_keys($allowedLocations),
            function ($carry, $location) use ($repo, $playerId, $allowedLocations) {
                $cards = match ($allowedLocations[$location]) {
                    SortForSelection::BY_INDEX => $repo->loadAndSortByIndexFromLocation($location, $playerId),
                    SortForSelection::BY_USAGE => $repo->loadAndSortByUsageFromLocation($location, $playerId),
                };
                return $carry + [$location => $cards];
            },
            []
        );
        $this->repo = $repo;
    }

    public function take(array $card) : PlayerCard {
        $selected = $this->peek($card);
        $this->taken[$selected->id] = true;
        return $selected;
    }

    public function peek(array $card) : PlayerCard {
        $cardGroup = $this->locations[$card['zone']] ?? null;
        if($cardGroup == null)
            throw new \BgaUserException("Invalid {$this->usage} - location not allowed");
        /** @var Card $selected */
        $selected = $cardGroup[$card['index']] ?? null;
        if ($selected === null)
            throw new \BgaUserException("Invalid {$this->usage} - unexpected index");
        $selected = $selected->withIndex($card['index']);
        if ($selected->fullName !== $card['name'])
            throw new \BgaUserException("Invalid {$this->usage} - card name mismatch: expected {$selected->fullName} but was {$card['name']}");
        if($this->taken[$selected->id] ?? false)
            throw new \BgaUserException("Invalid {$this->usage} - duplicate card");
        return $this->repo->createPlayerCard($selected);
    }
}