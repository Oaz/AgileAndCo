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

class CardsRepository
{
    public function __construct(array $groups, array $details, IDeckAdapter $deckAdapter)
    {
        $this->groups = $groups;
        $this->details = $details;
        $this->deck = $deckAdapter;
        $cards = array_reduce(array_keys($groups), function ($carry, $groupName) use ($groups) {
            return array_merge($carry, array_map(
                fn($index) => new Card(0, $groupName, $groups[$groupName][$index], 0, $groupName, $index),
                array_keys($groups[$groupName])));
        }, []);
        $this->cardsReference = array_combine(array_map(fn($c) => $c->fullName, $cards), $cards);
    }

    private array $groups;
    private array $details;
    private array $cardsReference;
    public readonly IDeckAdapter $deck;

    public function getAll($groupName): array
    {
        return array_map(function ($a) use ($groupName) {
            return $groupName . '_' . $a;
        }, $this->groups[$groupName]);
    }

    public function getIndex($groupName, $cardName): int
    {
        return array_search($cardName, $this->groups[$groupName]);
    }

    public function getActivities(): array
    {
        return $this->getAll('ACTIVITY');
    }

    public function getActivityIndex($activityName): int
    {
        return array_search($activityName, $this->getActivities());
    }

    public function getCardsInLocationSortedByIndexes(string $location, int $playerId): array
    {
        return array_map(function ($card) {
            if ($card === false)
                return false;
            return $card->fullName;
        }, $this->loadAndSortByIndexFromLocation($location, $playerId));
    }

    public function loadAndSortByIndexFromLocation(string $location, ?int $playerId): array
    {
        $cards = $this->loadFromLocation($location, playerId: $playerId);
        usort($cards, fn($a, $b) => $a->index - $b->index);
        $result = [];
        foreach ($cards as $card) {
            while (count($result) < $card->index) {
                $result[] = false;
            }
            $result[] = $card;
        }
        return $result;
    }

    public function getCardsInLocationSortedByUsage(string $location, int $playerId): array
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadAndSortByUsageFromLocation($location, $playerId));
    }

    public function loadAndSortByUsageFromLocation(string $location, ?int $playerId): array
    {
        $cards = $this->loadFromLocation($location, playerId: $playerId);
        usort($cards, [$this, 'naturalOrder']);
        return $cards;
    }

    public function naturalOrder(PlayerCard $a, PlayerCard $b): int
    {
        if ($a->type != $b->type) {
            $types = array_keys($this->groups);
            $ia = array_search($a->type, $types);
            $ib = array_search($b->type, $types);
            return $ia - $ib;
        }
        if ($a->cost != $b->cost)
            return $a->cost - $b->cost;
        if ($a->name == $b->name)
            return 0;
        return $a->name > $b->name ? 1 : -1;
    }

    public function listCards($location, ?int $index = null, ?int $playerId = null)
    {
        return array_map(function ($card) {
            return $card->fullName;
        }, $this->loadFromLocation($location, $index, $playerId));
    }

    public function listCardIds($location, ?int $index = null, ?int $playerId = null)
    {
        return array_map(function ($card) {
            return $card->id;
        }, $this->loadFromLocation($location, $index, $playerId));
    }

    public function getSingleCard($location, ?int $index = null, ?int $playerId = null)
    {
        $cards = $this->loadAndSortByIndexFromLocation($location, $playerId);
        if (count($cards) < $index)
            throw new \BgaUserException("No card found at location '$location', index '$index', player ID '$playerId'");
        return $cards[$index ?? 0];
    }

    public function loadFromLocation(string $location, ?int $index = null, ?int $playerId = null): array
    {
        return array_map(function ($data) {
            return $this->createCard($data);
        }, $this->deck->getCardsInLocation($location, $index, $playerId));
    }

    public function createCard(array $data): PlayerCard
    {
        $group = $this->groups[$data['type']];
        $card = new Card(
            $data['id'], $data['type'], $group[$data['type_arg']],
            $data['player_id'], $data['location'], $data['index']
        );
        return $this->createPlayerCard($card);
    }

    public function createCardTemplate(string $fullName): PlayerCard
    {
        return $this->createPlayerCard($this->cardsReference[$fullName]);
    }

    public function createPlayerCard(Card $card): PlayerCard
    {
        $details = $this->details[$card->fullName] ?? [];
        $cost = $details['cost'] ?? 0;
        $score = $details['score'] ?? 0;
        return new PlayerCard($card, $cost, $score);
    }

    public function getCards(string $fullName): array
    {
        $cardReference = $this->cardsReference[$fullName];
        return $this->deck->getCardsOfType($cardReference->type, $cardReference->index);
    }

    public function moveCardsToLocation(array $fullNames, string $location, ?int $playerId = null): void
    {
        $existingCards = $this->loadFromLocation($location, playerId: $playerId);
        $nextIndex = count($existingCards);
        $moved = [];
        foreach ($fullNames as $fullName) {
            $deck_cards = $this->getCards($fullName);
            foreach ($deck_cards as $deck_card) {
                $cardId = $deck_card['id'];
                if (isset($moved[$cardId]))
                    continue;
                $this->deck->moveCard($cardId, $location, index: $nextIndex++, playerId: $playerId);
                $moved[$cardId] = true;
                break;
            }
        }
    }

    public function moveCardsFromToLocation(string $fromLocation, string $toLocation, ?int $playerId = null): void
    {
        $cardsToMove = $this->loadFromLocation($fromLocation, playerId: $playerId);
        $existingCards = $this->loadFromLocation($toLocation, playerId: $playerId);
        $nextIndex = count($existingCards);
        $moved = [];
        foreach ($cardsToMove as $card) {
            if (isset($moved[$card->id]))
                continue;
            $this->deck->moveCard($card->id, $toLocation, index: $nextIndex++, playerId: $playerId);
            $moved[$card->id] = true;
        }
    }

}